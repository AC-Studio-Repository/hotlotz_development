<?php

namespace  App\Modules\Xero\Http\Controllers;

use Carbon\Carbon;
use App\XeroErrorLog;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Modules\Xero\Models\XeroItem;
use Illuminate\Support\Facades\Redis;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Illuminate\Support\Facades\Artisan;
use Webfox\Xero\OauthCredentialManager;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Xero\Models\XeroInvoice;
use App\Modules\Xero\Models\XeroTracking;
use App\Events\Xero\XeroAuctionInvoiceEvent;
use App\Modules\Xero\Events\BillWasPublished;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Xero\Events\InvoiceWasPublished;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes;
use App\Modules\Xero\Repositories\XeroControlRepository;
use App\Modules\Xero\Webhook\Updated as XeroWebhookUpdate;
use App\Modules\Xero\Accounting\Automate as AccountingAutomate;

class XeroController extends Controller
{
    public function __construct(
        XeroWebhookUpdate $xeroWebhookUpdate,
        AccountingAutomate $accountingAutomate,
        XeroControlRepository $xeroControlRepository
    ) {
        $this->xeroWebhookUpdate = $xeroWebhookUpdate;
        $this->accountingAutomate = $accountingAutomate;
        $this->xeroControlRepository = $xeroControlRepository;
    }

    public function panel(OauthCredentialManager $xeroCredentials)
    {
        return view('xero::panel', compact('xeroCredentials'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables()
    {
        $query = XeroInvoice::with(
            [
            'auction', 'buyer', 'seller'
            ]
        )->whereIn('status', [0, 2, 3])->select('xero_invoices.*')->get();

        $unique = $query->unique(
            function ($item) {
                return $item['buyer_id'].$item['seller_id'].$item['auction_id'];
            }
        );

        $result = $unique->values()->all();

        return Datatables::of($result)
            ->addColumn(
                'items',
                function ($xeroInvoice) {
                    $xeroItems = XeroInvoice::where(
                        [
                        ['auction_id', '=', $xeroInvoice->auction_id],
                        ['buyer_id', '=', $xeroInvoice->buyer_id],
                        ['seller_id', '=', $xeroInvoice->seller_id],
                        ]
                    )->get();
                    $items = [];

                    for ($i=0; $i < sizeof($xeroItems); $i++) {
                        if ($xeroItems[$i]->item != null) {
                            $items[] = '<a target="_blank" href="'.route('item.items.show', $xeroInvoice->item->id).'"">' . $xeroItems[$i]->item->name . '</a>';
                        }
                    }

                    $items = new HtmlString(implode(',<br> ', $items));

                    return $items;
                }
            )
        ->addColumn(
            'totalprice',
            function ($xeroInvoice) {
                $xeroItems = XeroInvoice::where(
                    [
                    ['auction_id', '=', $xeroInvoice->auction_id],
                    ['buyer_id', '=', $xeroInvoice->buyer_id],
                    ['seller_id', '=', $xeroInvoice->seller_id],
                    ]
                )->get();

                $totalPrice = (float) 0;

                for ($i=0; $i < sizeof($xeroItems); $i++) {
                    if ($xeroItems[$i]->item != null) {
                        $totalPrice += $xeroItems[$i]->price;
                    }
                }

                return $totalPrice;
            }
        )
        ->addColumn(
            'created_at_utc',
            function ($xeroInvoice) {
                return $xeroInvoice->created_at->format('M d Y H:i');
            }
        )
        ->addColumn(
            'auction',
            function ($xeroInvoice) {
                return new HtmlString('<a target="_blank" href="'.route('auction.auctions.show', $xeroInvoice->auction->id).'">'. $xeroInvoice->auction->title.'</a>');
            }
        )
        ->addColumn(
            'seller',
            function ($xeroInvoice) {
                return new HtmlString('<a target="_blank" href="'.route('customer.customers.show', $xeroInvoice->seller->id).'">'. $xeroInvoice->seller->fullname.' ( '. $xeroInvoice->seller->ref_no .' )</a>');
            }
        )
        ->addColumn(
            'buyer',
            function ($xeroInvoice) {
                return new HtmlString('<a target="_blank" href="'.route('customer.customers.show', $xeroInvoice->buyer->id).'">'. $xeroInvoice->buyer->fullname.' ( '. $xeroInvoice->buyer->ref_no .' )</a>');
            }
        )
        ->addColumn(
            'action',
            function ($xeroInvoice) {
                $button = '';
                $billButton = '<a href="'.route('xero.makeinvoice', ['id' => $xeroInvoice->id, 'type' => 'bill']).'" class="btn btn-xs btn-outline-info btn-show-on-tr-hover float-left mb-1"> Make Bill</a>';
                $invoiceButton = '<a href="'.route('xero.makeinvoice', ['id' => $xeroInvoice->id, 'type' => 'invoice']).'" class="btn btn-xs btn-outline-success btn-show-on-tr-hover float-left mb-1"> Make Invoice</a>';
                $bothButton = '<a href="'.route('xero.makeinvoice', $xeroInvoice->id).'" class="btn btn-xs btn-outline-warning btn-show-on-tr-hover float-left"> Make Both</a>';

                return $invoiceButton.$billButton.$bothButton;
            }
        )
        ->make(true);
    }

    public function accountServices()
    {
        return view('xero::service');
    }

    public function accountServicesEdit(XeroItem $xeroItem)
    {
        return view('xero::edit_service', compact('xeroItem'));
    }

    public function accountServicesSync(XeroItem $xeroItem, OauthCredentialManager $xeroCredentials, AccountingApi $apiInstance)
    {
        $item = $apiInstance->getItem($xeroCredentials->getTenantId(), $xeroItem->xero_product_id);
        $value = json_decode($item->__toString())->Items[0];

        $xeroItem->update(array(
            'item_code' => $value->Code,
            'item_name' => $value->Name,
            'purchases_description' => isset($value->PurchaseDescription) ? $value->PurchaseDescription : null,
            'purchases_account' => isset($value->PurchaseDetails->AccountCode) ? $value->PurchaseDetails->AccountCode: 0,
            'sales_description' => isset($value->Description) ? $value->Description : null,
            'sales_account' => isset($value->SalesDetails->AccountCode) ? $value->SalesDetails->AccountCode : 0,
            'xero_product_id' => $value->ItemID
        ));

        flash()->success(__($xeroItem->item_code. ' has been synced'));

        return redirect()->route('xero.account.services');
    }

    public function accountServicesSyncAll(OauthCredentialManager $xeroCredentials, AccountingApi $apiInstance)
    {
        $items = $apiInstance->getItems($xeroCredentials->getTenantId());
        foreach (json_decode($items->__toString())->Items as $key => $value) {
            if (!str_contains($value->Code, '/')) {
                XeroItem::updateOrCreate(array(
                    'item_code' => $value->Code,
                    'item_name' => $value->Name,
                    'purchases_description' => isset($value->PurchaseDescription) ? $value->PurchaseDescription : null,
                    'purchases_account' => isset($value->PurchaseDetails->AccountCode) ? $value->PurchaseDetails->AccountCode: 0,
                    'sales_description' => isset($value->Description) ? $value->Description : null,
                    'sales_account' => isset($value->SalesDetails->AccountCode) ? $value->SalesDetails->AccountCode : 0,
                    'xero_product_id' => $value->ItemID
                ));
            }
        }
        flash()->success(__(' All account has been synced'));

        return redirect()->route('xero.account.services');
    }

    public function accountServicesUpdate(XeroItem $xeroItem, Request $request, OauthCredentialManager $xeroCredentials, AccountingApi $apiInstance)
    {
        $xeroItem->sales_description = $request->sales_description;
        $xeroItem->save();

        $item = new \XeroAPI\XeroPHP\Models\Accounting\Item;

        $item->setCode($xeroItem->item_code)
            ->setDescription($request->sales_description);

        $apiInstance->updateItem($xeroCredentials->getTenantId(), $xeroItem->xero_product_id, $item);

        flash()->success(__('Xero Account Services has been updated'));

        return redirect()->route('xero.account.services');
    }

    public function accountServicesDelete(XeroItem $xeroItem)
    {
        $name = $xeroItem->item_code;
        $xeroItem->delete();

        flash()->success(__($name . ' has been removed'));

        return redirect()->route('xero.account.services');
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountServicesDatatables()
    {
        $query = XeroItem::select('xero_items.*')->get();

        return Datatables::of($query)
            ->addColumn(
                'action',
                function ($xeroItem) {
                    return '<a href="'.route('xero.account.services.edit', $xeroItem->id).'" class="btn btn-xs btn-outline-warning btn-show-on-tr-hover float-left mb-1"> Edit</a>
                    <a href="'.route('xero.account.services.sync', $xeroItem->id).'" class="btn btn-xs btn-outline-success btn-show-on-tr-hover float-left mb-1"> Sync</a>
                    <a href="'.route('xero.account.services.delete', $xeroItem->id).'" class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-left mb-1"> Remove</a>';
                }
            )
        ->make(true);
    }

    public function trackingCategories()
    {
        $businesses = XeroTracking::where('type', 'Business')->get();
        $categories = XeroTracking::where('type', 'Category')->get();

        return view('xero::tracking', compact('businesses', 'categories'));
    }

    public function trackingCategoriesUpdate(XeroTracking $xeroTracking, Request $request, OauthCredentialManager $xeroCredentials, AccountingApi $apiInstance)
    {
        $xeroTracking->name = $request->name;
        $xeroTracking->save();

        $option = new \XeroAPI\XeroPHP\Models\Accounting\TrackingOption;
        $option->setName($request->name);
        $apiInstance->updateTrackingOptions($xeroCredentials->getTenantId(), $xeroTracking->xero_tracking_category_id, $xeroTracking->xero_tracking_option_id, $option);

        flash()->success(__('Xero Tracking Categories has been updated'));

        return redirect()->route('xero.tracking.categories');
    }

    public function publishInvoice($invoice_id = 'all', $auction_id = null, $local = 'local')
    {
        if ($invoice_id != 'all') {
            CustomerInvoice::where('id', $invoice_id)
            ->where('type', 'invoice')
            ->update(array('active' => 1));
        }

        if ($auction_id != null) {
            CustomerInvoice::where('auction_id', $auction_id)
            ->where('type', 'invoice')
            ->whereHas('customer', function ($q) use ($local) {
                if ($local == 'local') {
                    $q->where('country_of_residence', 702);
                } else {
                    $q->where('country_of_residence', '!=', 702);
                }
            })->update(array('active' => 1));
        }

        flash()->success(__('Invoice(s) has been published'));

        event(new InvoiceWasPublished($invoice_id, $auction_id, $local));

        return redirect()->back();
    }

    public function publishBill($invoice_id = 'all', $auction_id = null)
    {
        if ($invoice_id != 'all') {
            DB::table('customer_invoices')->where('id', '=', $invoice_id)->where('type', 'bill')->update(array('active' => 1));
        }

        if ($auction_id != null) {
            DB::table('customer_invoices')->where('auction_id', '=', $auction_id)->where('type', 'bill')->update(array('active' => 1));
        }

        flash()->success(__('Bill(s) has been published'));

        event(new BillWasPublished($invoice_id, $auction_id));

        return redirect()->back();
    }

    public function syncXeroInvoiceUpdate()
    {
        $redis = Redis::connection();

        $invoices = json_decode($redis->get(':webhook:invoices'));

        if ($invoices == null) {
            $invoices = [];
        } else {
            $invoices = array_unique($invoices);
        }

        return view('xero::sync_invoice_update', compact('invoices'));
    }

    public function syncXeroInvoice(Request $request)
    {
        $redis = Redis::connection();

        $arrInvoices = json_decode($request->invoice_ids);
        $strInvoices = implode(',', array_unique($arrInvoices));

        $invoices = $this->accountingAutomate->getAllXeroInvoice(null, null, $strInvoices);
        foreach ($invoices as $invoice) {
            $this->xeroWebhookUpdate->invoiceUpdated($invoice);
        }
        $redis->del(':webhook:invoices');

        flash()->success(__('Invoice(s) has been updated'));

        return redirect()->back();
    }

    public function syncInvoice($id)
    {
        $invoice = $this->accountingAutomate->getInvoice($id);

        if ($invoice != null) {
            $this->xeroWebhookUpdate->invoiceUpdated($invoice);
            flash()->success(__('Invoice has been synced'));

            return redirect()->back();
        } else {
            flash()->error(__('Invoice sync failed'));

            return redirect()->back();
        }
    }

    public function generateInvoiceUrl($id)
    {
        $customerInvoice = new CustomerInvoice;
        $url = $customerInvoice->url($id);

        if ($url != null) {
            return redirect($customerInvoice->url($id));
        } else {
            flash()->error(__('Invoice url not found'));

            return redirect()->back();
        }
    }

    public function splitSettlement($invoice_id, Request $request, OauthCredentialManager $xeroCredentials, AccountingApi $apiInstance)
    {
        $itemIds = json_decode($request->ids);
        if ($itemIds == null || count($itemIds) == 0) {
            flash()->error(__('Error: :msg', ['msg' => 'At least check one item in item list']));
            return redirect()->back();
        }

        $items = Item::whereIn('id', $itemIds)->get();
        $sellerInvoice = CustomerInvoice::where('invoice_id', $invoice_id)->where('type', 'bill')->first();

        $sellerInvoiceItemsOld = CustomerInvoiceItem::where('customer_invoice_id', $sellerInvoice->id)->whereNotIn('item_id', $itemIds)->get();
        $sellerInvoiceItemsNew = CustomerInvoiceItem::where('customer_invoice_id', $sellerInvoice->id)->whereIn('item_id', $itemIds)->get();

        $setSellerLineItemsOld = [];
        foreach ($sellerInvoiceItemsOld as $sellerInvoiceItem) {
            $sellerLineItems = $this->xeroControlRepository->getSellerLineItems($sellerInvoiceItem->price, $sellerInvoiceItem->item, $sellerInvoice->customer, $sellerInvoice->invoice_type, $sellerInvoice->invoice_type);
            foreach ($sellerLineItems as $lineItem) {
                $setSellerLineItemsOld[] = $lineItem;
            }
        }

        $oldBill = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $oldBill->setLineItems($setSellerLineItemsOld);
        $oldBill = $apiInstance->updateInvoice($xeroCredentials->getTenantId(), $invoice_id, $oldBill);
        $sellerInvoice->invoice_url = null;
        $sellerInvoice->save();
        \Log::channel('xeroLog')->info('Success updated Seller Bill '.$oldBill->getInvoices()[0]->getInvoiceId());

        $setSellerLineItemsNew = [];
        foreach ($sellerInvoiceItemsNew as $sellerInvoiceItem) {
            $sellerLineItems = $this->xeroControlRepository->getSellerLineItems($sellerInvoiceItem->price, $sellerInvoiceItem->item, $sellerInvoice->customer, $sellerInvoice->invoice_type, $sellerInvoice->invoice_type);
            foreach ($sellerLineItems as $lineItem) {
                $setSellerLineItemsNew[] = $lineItem;
            }
            $sellerInvoiceItem->delete();
        }

        $brandingThemeId = $oldBill->getInvoices()[0]->getBrandingThemeId();

        $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
        $contact->setContactId($sellerInvoice->customer->contact_id);

        $newBill = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $newBill->setInvoiceNumber($this->setSettlementInvoiceNumber($sellerInvoice->invoice_type))
            ->setDueDate(Carbon::now()->addWeeks(3))
            ->setContact($contact)
            ->setLineItems($setSellerLineItemsNew)
            ->setStatus('AUTHORISED')
            ->setType(\XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCPAY)
            ->setBrandingThemeId($brandingThemeId)
            ->setLineAmountTypes(\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes::INCLUSIVE);

        $newBill = $apiInstance->createInvoices($xeroCredentials->getTenantId(), $newBill, true);
        \Log::channel('xeroLog')->info('Success Seller Bill '.$newBill->getInvoices()[0]->getInvoiceId());

        $customerBill = $this->xeroControlRepository->createCustomerInvoice($sellerInvoice->customer_id, $newBill->getInvoices()[0]->getInvoiceId(), $sellerInvoice->invoice_type, 'bill', $newBill->getInvoices()[0]->__toString(), $sellerInvoice->auction_id);

        foreach ($items as $index => $item) {
            if ($item->is_hotlotz_own_stock == 'N') {
                $item->bill_id = $newBill->getInvoices()[0]->getInvoiceId();
                $item->save();
            }
            $this->xeroControlRepository->createCustomerInvoiceItem($customerBill->id, $item->id, $item->sold_price);
        }

        flash()->success(__('Settlement has been splited. <a href="'. route('customer.customers.splitSettlement', [$sellerInvoice->customer_id, $newBill->getInvoices()[0]->getInvoiceId()]) .'">Check Here</a>'));

        return redirect()->back();
    }

    protected function setSettlementInvoiceNumber($type = null)
    {
        $latestInvoiceId = DB::table('customer_invoices')->latest('id')->pluck('id')->first() + 1;
        if ($type == 'marketplace') {
            return 'RTP-'.str_pad($latestInvoiceId, 7, "0", STR_PAD_LEFT);
        } else {
            return 'DNP-'.str_pad($latestInvoiceId, 7, "0", STR_PAD_LEFT);
        }
    }

    public function get429()
    {
        return $this->accountingAutomate->get429();
    }

    public function syncSettlementUpdate(Request $request)
    {
        $redis = Redis::connection();

        try {
            $where = 'Type=="'. \XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCPAY .'" AND Status=="'. \XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_AUTHORISED .'"';
            $if_modified_since = $settlementSyncDate ?? now();

            $settlementSyncDate = $redis->get(':settlement:settlement_sync_date');

            if ($settlementSyncDate == null) {
                $settlementSyncDate = date('Y-m-d', strtotime("-7 days"));
                $settlementSyncDate = Carbon::createFromFormat('Y-m-d', $settlementSyncDate)->format('Y-m-d');
            }

            $if_modified_since = $settlementSyncDate . ' 00:00:00';

            $invoices = $this->accountingAutomate->getAllXeroInvoice($where, $if_modified_since);
            $i = 0;
            foreach ($invoices as $invoice) {
                $this->xeroWebhookUpdate->invoiceUpdated($invoice);
                $i++;
            }

            $settlementSyncDate = date('Y-m-d', strtotime("7 days"));
            $settlementSyncDate = Carbon::createFromFormat('Y-m-d', $settlementSyncDate)->format('Y-m-d');

            $redis->set(':settlement:settlement_sync_date', $settlementSyncDate);

            flash()->success(__('Total '.$i.' settlement(s) has been updated'));

            return redirect()->back();
        } catch (\Throwable $th) {
            $settlementSyncDate = date('Y-m-d');
            $settlementSyncDate = Carbon::createFromFormat('Y-m-d', $settlementSyncDate)->format('Y-m-d');

            $redis->set(':settlement:settlement_sync_date', $settlementSyncDate);

            flash()->error(__('Settlement(s) update fail!'));

            return redirect()->back();
        }
    }

    public function error()
    {
        return view('xero::error');
    }

    public function errorDatatables()
    {
        $query = XeroErrorLog::with(
            [
            'buyer', 'seller', 'item'
            ]
        )->select('xero_error_logs.*')->get();

        $unique = $query->unique(
            function ($item) {
                return $item['buyer_id'].$item['seller_id'].$item['unique_key'];
            }
        );

        $result = $unique->values()->all();

        return Datatables::of($result)
            ->addColumn(
                'items',
                function ($data) {
                    $xeroErrorItems = XeroErrorLog::where(
                        [
                        ['unique_key', '=', $data->unique_key],
                        ['buyer_id', '=', $data->buyer_id],
                        ['seller_id', '=', $data->seller_id],
                        ]
                    )->get();
                    $items = [];

                    for ($i=0; $i < sizeof($xeroErrorItems); $i++) {
                        if ($xeroErrorItems[$i]->item != null) {
                            $items[] = '<a target="_blank" href="'.route('item.items.show', $xeroErrorItems[$i]->item->id).'"">' . $xeroErrorItems[$i]->item->name . '</a>';
                        }
                    }

                    $items = new HtmlString(implode(',<br> ', $items));

                    return $items;
                }
            )
        ->addColumn(
            'amount',
            function ($data) {
                return XeroErrorLog::where(
                    [
                    ['unique_key', '=', $data->unique_key],
                    ['buyer_id', '=', $data->buyer_id],
                    ['seller_id', '=', $data->seller_id],
                    ]
                )->sum('amount');
            }
        )
        ->addColumn(
            'created_at',
            function ($data) {
                return $data->created_at->format('M d Y H:i');
            }
        )
        ->addColumn(
            'seller',
            function ($data) {
                return new HtmlString('<a target="_blank" href="'.route('customer.customers.show', $data->seller->id).'">'. $data->seller->fullname.' ( '. $data->seller->ref_no .' )</a>');
            }
        )
        ->addColumn(
            'buyer',
            function ($data) {
                return new HtmlString('<a target="_blank" href="'.route('customer.customers.show', $data->buyer->id).'">'. $data->buyer->fullname.' ( '. $data->buyer->ref_no .' )</a>');
            }
        )
        ->addColumn(
            'action',
            function ($data) {
                $action = '<a href="'.route('xero.error.delete', $data->id).'" class="btn btn-xs btn-outline-success btn-show-on-tr-hover float-left mb-1"> Resolve</a>';

                return $action;
            }
        )
        ->make(true);
    }

    public function errorDelete($id)
    {
        $data = XeroErrorLog::find($id);

        $xeroErrorLogs = XeroErrorLog::where([
            ['unique_key', '=', $data->unique_key],
            ['buyer_id', '=', $data->buyer_id],
            ['seller_id', '=', $data->seller_id],
            ])->get();

        foreach ($xeroErrorLogs as $xeroError) {
            $xeroError->delete();
        }

        flash()->success(__('Error Resloved!'));

        return redirect()->back();
    }

    public function automateInvoiceItems()
    {
        return view('xero::automate_invoice_items');
    }

    public function checkInvoiceItems(Request $request)
    {
        $auctionID = $request->auction_id;

        $auctionItems = AuctionItem::where('auction_id', $auctionID)->whereIn('status', ['Paid','Sold', 'Settled'])->get();

        $xeroInvoiceLists = XeroInvoice::where('auction_id', $auctionID)->get();

        if ($xeroInvoiceLists->count() == $auctionItems->count()) {
            flash()->success(__('All of items are already created!'));
            return redirect()->back();
        }

        $missingLists = $auctionItems->whereNotIn('item_id', $xeroInvoiceLists->pluck('item_id'));

        foreach ($missingLists as $auctionItem) {
            ## Xero Item Event
            $xero_data = [
                'type' => 'auction',
                'hammer_price' => $auctionItem->sold_price,
                'sold_price_inclusive_gst' => $auctionItem->sold_price_inclusive_gst,
                'sold_price_exclusive_gst' => $auctionItem->sold_price_exclusive_gst,
                'item_id' => $auctionItem->item_id,
                'buyer_id' => $auctionItem->buyer_id,
                'seller_id' => Item::where('id', $auctionItem->item_id)->first()->customer_id,
                'auction_id' => $auctionItem->auction_id,
            ];
            event(new XeroAuctionInvoiceEvent($xero_data));
        }

        flash()->success(__('Total '. $missingLists->count() .' missing items found & sync in xero invoice table!'));
        return redirect()->back();
    }
}
