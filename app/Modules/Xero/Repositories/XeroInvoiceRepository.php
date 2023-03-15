<?php

namespace App\Modules\Xero\Repositories;

use App\XeroErrorLog;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Modules\Xero\Models\XeroItem;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use App\Modules\Customer\Models\Customer;
use App\Modules\Xero\Models\XeroTracking;
use App\Events\Client\AuctionInvoiceEvent;
use App\Events\Client\PaymentReceiptEvent;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;
use XeroAPI\XeroPHP\Models\Accounting\TaxType;
use App\Events\Xero\ThirdPartyPaymentAlertEvent;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\OrderSummary\Models\OrderSummary;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes;
use App\Modules\Customer\Models\CustomerMarketplaceItem;
use App\Modules\Xero\Repositories\XeroContactRepository;
use App\Modules\Xero\Repositories\XeroControlRepository;
use App\Modules\Xero\Repositories\XeroProductRepository;
use App\Modules\OrderSummary\Http\Repositories\OrderSummaryRepository;

class XeroInvoiceRepository
{
    public $apiInstance;

    public $exclusiveBrandingThemeId;

    public $inclusiveBrandingThemeId;

    public function __construct(
        XeroContactRepository $xeroContactRepository,
        XeroProductRepository $xeroProductRepository,
        XeroControlRepository $xeroControlRepository,
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance,
        OrderSummaryRepository $orderSummaryRepository
    ) {
        $this->xeroContactRepository = $xeroContactRepository;
        $this->xeroProductRepository = $xeroProductRepository;
        $this->xeroControlRepository = $xeroControlRepository;
        $this->xeroCredentials = $xeroCredentials;
        $this->apiInstance = $apiInstance;
        $this->orderSummaryRepository = $orderSummaryRepository;
        $this->exclusiveBrandingThemeId = config('services.xero.exclusive_branding_theme_id');
        $this->inclusiveBrandingThemeId = config('services.xero.inclusive_branding_theme_id');
    }

    public function init($arg)
    {
        $this->apiInstance = $arg;
    }

    public function refreshCredential()
    {
        $xeroConfig = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration();
        $this->xeroCredentials->refresh();
        $xeroConfig->setAccessToken($this->xeroCredentials->getAccessToken());
    }

    public function createMarketPlaceInvoice($payload, $only = null, $date = null)
    {
        $this->refreshCredential();
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $arr_invoices = [];
        $bill_invoices = [];
        $buyerLineItems = [];
        $sellerItems = [];
        $xeroErrorLogIDs = [];
        $uniqueKey = Str::random(10);

        foreach ($payload['items'] as $eachItem) {
            $item = Item::findOrFail($eachItem['id']);

            $business = XeroTracking::where('id', 8)->first()->name;
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = XeroTracking::where('id', 3)->first()->name;
                $xeroItem = XeroItem::where('item_code', 'MP - Fixed Price')->first();
            } else {
                $xeroItem = XeroItem::where('item_code', 'OS MP - Fixed Price')->first();
            }
            $category = $item->category->name;

            $buyer = Customer::findOrFail($payload['customer_id']);

            $itemCode = $xeroItem->item_code;
            $saleAccount = $xeroItem->sales_account;

            // $taxType = TaxType::ZERORATEDOUTPUT;

            // if ($buyer->buyer_gst_status == 1) {
            $taxType = "OUTPUTY23";
            // }

            if ($item->is_hotlotz_own_stock == 'Y') {
                $itemCode = $item->item_number;
                $saleAccount = 200;
                // $taxType = TaxType::NONE;
            }

            $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
            $lineItem->setItemCode($itemCode)
                ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
                ->setQuantity(1)
                ->setUnitAmount($eachItem['price'])
                ->setTaxType($taxType)
                ->setAccountCode($saleAccount)
                ->setTracking($this->xeroControlRepository->setTracking($business, $category));

            $buyerLineItems[] = $lineItem;

            if ($item->is_hotlotz_own_stock == 'N') {
                $sellerItems[$item->customer_id]['items'][] = $item;
                $sellerItems[$item->customer_id]['prices'][] = $eachItem['price'];
            }

            $invoiceID = null;
            $xeroErrorLog = XeroErrorLog::create([
                'seller_id' => $item->customer_id,
                'buyer_id' => $item->buyer_id,
                'item_id' => $item->id,
                'amount' => $item->sold_price,
                'type' => 'marketplace invoice',
                'invoice_id' => $invoiceID,
                'unique_key' => $uniqueKey
            ]);
            $xeroErrorLogIDs[] = $xeroErrorLog->id;
        }

        $buyerContactID = $this->xeroContactRepository->createOrGetContact($payload['customer_id']);

        $buyerContact = $this->xeroControlRepository->setXeroContact($buyerContactID);

        $buyerInvoice = $this->setBuyerInvoice($buyerContact, $buyerLineItems, Invoice::STATUS_AUTHORISED, null, 'Marketplace Invoice', LineAmountTypes::INCLUSIVE, $date);

        $xeroErrorLogs = XeroErrorLog::whereIn('id', $xeroErrorLogIDs)->get();
        foreach ($xeroErrorLogs as $xeroErrorLog) {
            $xeroErrorLog->delete();
        }

        $this->addInvoiceHistory($buyerInvoice->getInvoices()[0]->getInvoiceId(), 'Stripe transaction with '.$payload['payment_intent']);

        $this->createPayment($buyerInvoice->getInvoices()[0], $payload['payment_intent']);

        $finalInvoice = $apiInstance->getInvoice($xeroTenantId, $buyerInvoice->getInvoices()[0]->getInvoiceId());

        $customerInvoice = $this->xeroControlRepository->createCustomerInvoice($payload['customer_id'], $finalInvoice->getInvoices()[0]->getInvoiceId(), 'marketplace', 'invoice', $finalInvoice->getInvoices()[0]->__toString());

        foreach ($payload['items'] as $eachItem) {
            $this->xeroControlRepository->createCustomerInvoiceItem($customerInvoice->id, $eachItem['id'], $eachItem['price'], 'marketplace');
        }

        $thirdPartyPaymentAlertPayload['customer_id'] = $payload['customer_id'];
        $thirdPartyPaymentAlertPayload['invoice_id'] = $buyerInvoice->getInvoices()[0]->getInvoiceId();
        $thirdPartyPaymentAlertPayload['invoice_number'] = $buyerInvoice->getInvoices()[0]->getInvoiceNumber();
        $thirdPartyPaymentAlertPayload['amount'] = $buyerInvoice->getInvoices()[0]->getTotal();
        $thirdPartyPaymentAlertPayload['payment_method'] = $payload['payment_type'];

        event(new ThirdPartyPaymentAlertEvent($thirdPartyPaymentAlertPayload));

        $orderSummary['invoice_id'] = $buyerInvoice->getInvoices()[0]->getInvoiceId();
        $orderSummary['customer_id'] = $payload['customer_id'];
        $orderSummary['total'] = $buyerInvoice->getInvoices()[0]->getTotal();
        $orderSummary['from'] = 'marketplace';
        $orderSummary['type'] = $payload['shipType'] == 'yes' ? 'ship' : 'pickup';
        $orderSummary['status'] = OrderSummary::PAID;

        if ($payload['shipType'] == 'yes') {
            $orderSummary['address_id'] = $payload['addressId'];
        }

        $order = $this->orderSummaryRepository->create($orderSummary);

        \Log::channel('xeroLog')->info('Success Buyer Invoice '.$buyerInvoice->getInvoices()[0]->getInvoiceId());

        $itemNames = [];
        foreach ($payload['items'] as $eachItem) {
            $item = Item::findOrFail($eachItem['id']);
            if ($item->is_hotlotz_own_stock == 'Y') {
                $item->status = 'Settled';
            } else {
                $item->status = 'Paid';
            }
            $item->invoice_id = $buyerInvoice->getInvoices()[0]->getInvoiceId();

            if ($payload['shipType'] == 'yes') {
                $item->delivery_requested = "Y";
                $item->delivery_requested_date = date('Y-m-d H:i:s');
            }
            $item->save();

            $itemNames[] = $item->name;

            $order->items()->attach([$item->id]);
        }

        event(new PaymentReceiptEvent($payload['customer_id'], $itemNames));

        if ($only == null) {
            if (sizeof($sellerItems) > 0) {
                foreach ($sellerItems as $index => $sellerItem) {
                    $this->sellerXeroInvoice($index, $sellerItem['items'], $sellerItem['prices'], 'Hotlotz Marketplace', null, 'marketplace', 'marketplace');
                }
            }
        }

        return "Done Create MarketPlace Invoice : " . $buyerInvoice->getInvoices()[0]->getInvoiceId();
    }

    public function createAdhocInvoice($payload, $xeroTenantId, $apiInstance)
    {
        $sellerContactID = $this->xeroContactRepository->createOrGetContact($payload['seller_id']);

        $sellerContact = $this->xeroControlRepository->setXeroContact($sellerContactID);

        $arr_invoices = [];

        $sellerLineItems = [];

        $adhocCustomer = Customer::findOrFail($payload['seller_id']);

        foreach ($payload['items'] as $eachItem) {
            $taxType = TaxType::ZERORATEDOUTPUT;
            $xeroItem = XeroItem::findOrFail($eachItem['item_id']);
            if ($xeroItem->sale_tax_rate == 'SRF') {
                $taxType = "OUTPUTY23";
            }
            $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
            $lineItem->setItemCode($xeroItem->item_code)
                ->setDescription($xeroItem->sales_description . ' - ' . $eachItem['notes'])
                ->setQuantity(1)
                ->setUnitAmount($eachItem['price'])
                ->setAccountCode($xeroItem->sales_account)
                ->setTaxType($taxType)
                ->setTracking($this->xeroControlRepository->setTracking());

            $sellerLineItems[] = $lineItem;
        }

        $adhocInvoice = $this->setBuyerInvoice($sellerContact, $sellerLineItems, Invoice::STATUS_AUTHORISED, null, 'Adhoc Invoice');

        $customerInvoice = $this->xeroControlRepository->createCustomerInvoice($payload['seller_id'], $adhocInvoice->getInvoices()[0]->getInvoiceId(), 'adhoc', 'invoice', $adhocInvoice->getInvoices()[0]->__toString(), null, $payload['order_id']);

        foreach ($payload['items'] as $eachItem) {
            $this->xeroControlRepository->createCustomerInvoiceItem($customerInvoice->id, $eachItem['item_id'], $eachItem['price'], 'adhoc');
        }

        return "Create Invoice for Adchoc: " . $adhocInvoice->getInvoices()[0]->getInvoiceId();
    }

    public function addInvoiceHistory($invoiceId, $note)
    {
        $this->refreshCredential();
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $arr_history_records = [];

        $history_record = new \XeroAPI\XeroPHP\Models\Accounting\HistoryRecord;
        $history_record->setDetails($note);

        $arr_history_records[] = $history_record;

        $history_records = new \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords;
        $history_records->setHistoryRecords($arr_history_records);

        $apiInstance->createInvoiceHistory($xeroTenantId, $invoiceId, $history_records);
    }

    public function cancelInvoice($payload, $xeroTenantId, $apiInstance)
    {
        $item = Item::findOrFail($payload['item_id']);

        $sellerInvoice = CustomerInvoice::where('invoice_id', $item->bill_id)->where('type', 'bill')->first();
        $buyerInvoice = CustomerInvoice::where('invoice_id', $item->invoice_id)->where('type', 'invoice')->first();

        $sellerInvoiceItems = CustomerInvoiceItem::where('customer_invoice_id', $sellerInvoice->id)->where('item_id', '!=', $item->id)->where('cancel_sale', 0)->get();
        $buyerInvoiceItems = CustomerInvoiceItem::where('customer_invoice_id', $buyerInvoice->id)->where('item_id', '!=', $item->id)->where('cancel_sale', 0)->get();

        $setSellerLineItems = [];
        foreach ($sellerInvoiceItems as $sellerInvoiceItem) {
            $sellerLineItems = $this->xeroControlRepository->getSellerLineItems($sellerInvoiceItem->price, $sellerInvoiceItem->item, $sellerInvoice->customer, $sellerInvoice->invoice_type, $sellerInvoice->invoice_type);
            foreach ($sellerLineItems as $lineItem) {
                $setSellerLineItems[] = $lineItem;
            }
        }
        $bill = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        if ($sellerInvoiceItems->count() == 0) {
            // if($sellerInvoice->status == 'Awaiting Approval'){
            //     $bill->setStatus(\XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_DELETED);
            // }else{
            $bill->setStatus(\XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_VOIDED);
        // }
        } else {
            $bill->setLineItems($setSellerLineItems);
        }

        $apiInstance->updateInvoice($xeroTenantId, $item->bill_id, $bill);

        $setBuyerLineItems = [];
        foreach ($buyerInvoiceItems as $buyerInvoiceItem) {
            if ($buyerInvoice->invoice_type == 'auction') {
                $buyerLineItems = $this->xeroControlRepository->getBuyerLineItems($buyerInvoiceItem->price, $buyerInvoiceItem->item, $buyerInvoice->customer, $payload['auction_id']);
            }

            if ($buyerInvoice->invoice_type == 'private') {
                $buyerLineItems = $this->xeroControlRepository->getPrivateBuyerLineItems($buyerInvoiceItem->item->private_sale_price, $buyerInvoiceItem->item, $buyerInvoice->customer, $buyerInvoiceItem->item->private_sale_buyer_premium, $buyerInvoice->invoice_type);
            }

            foreach ($buyerLineItems as $lineItem) {
                $setBuyerLineItems[] = $lineItem;
            }
        }

        $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        if ($buyerInvoiceItems->count() == 0) {
            $invoice->setStatus(\XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_VOIDED);
        } else {
            $invoice->setLineItems($setBuyerLineItems);
        }

        $apiInstance->updateInvoice($xeroTenantId, $item->invoice_id, $invoice);

        $itemInvoices = CustomerInvoiceItem::where('item_id', $item->id)->get();
        foreach ($itemInvoices as $key => $itemInvoice) {
            $itemInvoice->delete();
        }
        $item->bill_id = null;
        $item->invoice_id = null;
        $item->save();

        return "Cancle Invoice Success " . $item->invoice_id;
    }

    public function creditInvoice($payload, $xeroTenantId, $apiInstance)
    {
        $item = Item::findOrFail($payload['item_id']);

        $sellerInvoice = CustomerInvoice::where('invoice_id', $item->bill_id)->where('type', 'bill')->first();
        $buyerInvoice = CustomerInvoice::where('invoice_id', $item->invoice_id)->where('type', 'invoice')->first();

        $sellerInvoiceItems = CustomerInvoiceItem::where('customer_invoice_id', $sellerInvoice->id)->where('item_id', '!=', $item->id)->where('cancel_sale', 0)->get();
        $buyerInvoiceItems = CustomerInvoiceItem::where('customer_invoice_id', $buyerInvoice->id)->where('item_id', '!=', $item->id)->where('cancel_sale', 0)->get();

        $cancelBuyerInvoiceItem = CustomerInvoiceItem::where('customer_invoice_id', $buyerInvoice->id)->where('item_id', $item->id)->first();
        $cancelSellerInvoiceItem = CustomerInvoiceItem::where('customer_invoice_id', $sellerInvoice->id)->where('item_id', $item->id)->first();

        $setSellerLineItems = [];
        foreach ($sellerInvoiceItems as $sellerInvoiceItem) {
            $sellerLineItems = $this->xeroControlRepository->getSellerLineItems($sellerInvoiceItem->price, $sellerInvoiceItem->item, $sellerInvoice->customer, $sellerInvoice->invoice_type, $sellerInvoice->invoice_type);
            foreach ($sellerLineItems as $lineItem) {
                $setSellerLineItems[] = $lineItem;
            }
        }

        $cancelSellerLineItems = $this->xeroControlRepository->getSellerLineItems($cancelSellerInvoiceItem->price, $cancelSellerInvoiceItem->item, $sellerInvoice->customer, $sellerInvoice->invoice_type, $sellerInvoice->invoice_type);

        $sellerCredit = 0;
        foreach ($cancelSellerLineItems as $lineItem) {
            $lineItem->setDescription('[Cancel Sale] '. $lineItem->getDescription());
            $sellerCredit += $lineItem->getUnitAmount();
            $setSellerLineItems[] = $lineItem;
        }

        $sellerCreditNote = $this->xeroControlRepository->createCreditNoteAuthorised($sellerInvoice->customer->contact_id, $cancelSellerLineItems, LineAmountTypes::INCLUSIVE, \XeroAPI\XeroPHP\Models\Accounting\CreditNote::TYPE_ACCPAYCREDIT);

        $resultSellerCreditNote = $apiInstance->createCreditNotes($xeroTenantId, $sellerCreditNote);
        $sellerCreditNoteId = $resultSellerCreditNote->getCreditNotes()[0]->getCreditNoteID();

        $bill = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;

        $bill->setLineItems($setSellerLineItems);

        $apiInstance->updateInvoice($xeroTenantId, $item->bill_id, $bill);

        $creditBill = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $creditBill->setInvoiceID($item->bill_id);

        $allocationBill = new \XeroAPI\XeroPHP\Models\Accounting\Allocation;
        $allocationBill->setInvoice($creditBill)
            ->setAmount($sellerCredit)
            ->setDate(Carbon::now());

        $apiInstance->createCreditNoteAllocation($xeroTenantId, $sellerCreditNoteId, $allocationBill);

        $setBuyerLineItems = [];
        foreach ($buyerInvoiceItems as $buyerInvoiceItem) {
            if ($buyerInvoice->invoice_type == 'auction') {
                $buyerLineItems = $this->xeroControlRepository->getBuyerLineItems($buyerInvoiceItem->price, $buyerInvoiceItem->item, $buyerInvoice->customer, $buyerInvoice->auction_id);
            }

            if ($buyerInvoice->invoice_type == 'private') {
                $buyerLineItems = $this->xeroControlRepository->getPrivateBuyerLineItems($buyerInvoiceItem->price, $buyerInvoiceItem->item, $buyerInvoice->customer, $buyerInvoiceItem->item->private_sale_buyer_premium, $buyerInvoice->invoice_type);
            }

            foreach ($buyerLineItems as $lineItem) {
                $setBuyerLineItems[] = $lineItem;
            }
        }

        if ($buyerInvoice->invoice_type == 'auction') {
            $cancelBuyerLineItems = $this->xeroControlRepository->getBuyerLineItems($cancelBuyerInvoiceItem->price, $item, $buyerInvoice->customer, $buyerInvoice->auction_id);
        }

        if ($buyerInvoice->invoice_type == 'private') {
            $cancelBuyerLineItems = $this->xeroControlRepository->getPrivateBuyerLineItems($cancelBuyerInvoiceItem->price, $item, $buyerInvoice->customer, $payload['private_sale_buyer_premium'], $buyerInvoice->invoice_type);
        }

        $buyerCredit = 0;
        foreach ($cancelBuyerLineItems as $lineItem) {
            $lineItem->setDescription('[Cancel Sale] '. $lineItem->getDescription());
            $buyerCredit += $lineItem->getUnitAmount();
            $setBuyerLineItems[] = $lineItem;
        }

        $buyerCreditNote = $this->xeroControlRepository->createCreditNoteAuthorised($buyerInvoice->customer->contact_id, $cancelBuyerLineItems, LineAmountTypes::INCLUSIVE, \XeroAPI\XeroPHP\Models\Accounting\CreditNote::TYPE_ACCRECCREDIT);

        $resultBuyerCreditNote = $apiInstance->createCreditNotes($xeroTenantId, $buyerCreditNote);
        $buyerCreditNoteId = $resultBuyerCreditNote->getCreditNotes()[0]->getCreditNoteID();

        $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $invoice->setLineItems($setBuyerLineItems);

        $apiInstance->updateInvoice($xeroTenantId, $item->invoice_id, $invoice);

        $creditInvoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $creditInvoice->setInvoiceID($item->invoice_id);

        $allocationInvoice = new \XeroAPI\XeroPHP\Models\Accounting\Allocation;
        $allocationInvoice->setInvoice($creditInvoice)
            ->setAmount($buyerCredit)
            ->setDate(Carbon::now());

        $apiInstance->createCreditNoteAllocation($xeroTenantId, $buyerCreditNoteId, $allocationInvoice);

        $itemInvoices = CustomerInvoiceItem::where('item_id', $item->id)->get();
        foreach ($itemInvoices as $key => $itemInvoice) {
            $itemInvoice->cancel_sale = 1;
            $itemInvoice->save();
        }

        return "Cancle Invoice Success " . $item->invoice_id;
    }

    public function createPayment($invoice, $paymentReference = null)
    {
        $this->refreshCredential();
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $invoice2 = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $invoice2->setInvoiceID($invoice->getInvoiceId());

        $bankaccount = new \XeroAPI\XeroPHP\Models\Accounting\Account;
        $bankaccount->setAccountID(config('services.xero.payment_account_id'));

        $payment = new \XeroAPI\XeroPHP\Models\Accounting\Payment;
        $payment->setInvoice($invoice2)
            ->setAccount($bankaccount)
            ->setInvoiceNumber($invoice->getInvoiceNumber())
            ->setReference($paymentReference)
            ->setAmount($invoice->getTotal());

        $apiInstance->createPayment($xeroTenantId, $payment);
    }

    public function sellerXeroInvoice($customer_id, array $items, array $prices, $ref, $auction_id = null, $type = 'auction', $sellerType = null, $date = null)
    {
        $customer = Customer::findOrFail($customer_id);

        if ($sellerType != null && $sellerType != 'auction') {
            $sellerLatestInvoice = $customer->invoices()->where('invoice_type', $sellerType)->where('auction_id', $auction_id)->where('type', 'bill')->latest()->first();
            $invoiceStatus = 'Awaiting Payment';
            $invoicType = Invoice::STATUS_AUTHORISED;
        } else {
            $sellerLatestInvoice = $customer->invoices()->where('invoice_type', $sellerType)->where('auction_id', $auction_id)->where('type', 'bill')->latest()->first();
            if ($type == 'marketplace') {
                $invoiceStatus = 'Awaiting Payment';
                $invoicType = Invoice::STATUS_AUTHORISED;
            } else {
                $invoiceStatus = 'Awaiting Payment';
                $invoicType = Invoice::STATUS_AUTHORISED;
            }
        }

        if ($type == 'marketplace' || $type == 'private') {
            $sellerLatestInvoice = null;
        }

        $sellerLineItems = [];
        $xeroErrorLogIDs = [];
        $uniqueKey = Str::random(10);

        foreach ($items as $index => $item) {
            $oldItemFilter = null;

            if ($sellerLatestInvoice) {
                $oldItemFilter = $sellerLatestInvoice->items->where('item_id', $item->id)->where('cancel_sale', 0)->first();
            }

            if (is_null($oldItemFilter)) {
                $getSellerLineItems = [];
                if ($item->is_hotlotz_own_stock == 'N') {
                    $getSellerLineItems = $this->xeroControlRepository->getSellerLineItems($prices[$index], $item, $customer, $type, $sellerType);
                }
                foreach ($getSellerLineItems as $lineItem) {
                    $sellerLineItems[] = $lineItem;
                }
            }
            $invoiceID = null;
            if ($sellerLatestInvoice) {
                $invoiceID = $sellerLatestInvoice->invoice_id;
            }
            $xeroErrorLog = XeroErrorLog::create([
                'seller_id' => $item->customer_id,
                'buyer_id' => $item->buyer_id,
                'item_id' => $item->id,
                'amount' => $item->sold_price,
                'type' => $type . ' bill',
                'invoice_id' => $invoiceID,
                'unique_key' => $uniqueKey
            ]);
            $xeroErrorLogIDs[] = $xeroErrorLog->id;
        }

        $sellerContactID = $this->xeroContactRepository->createOrGetContact($customer_id);

        $sellerContact = $this->xeroControlRepository->setXeroContact($sellerContactID);

        $sellerInvoice = $this->setSellerInvoice($sellerContact, $sellerLineItems, $sellerLatestInvoice, $invoicType, $ref, $type, $date);
        \Log::channel('xeroLog')->info('Success Seller Bill '.$sellerInvoice->getInvoices()[0]->getInvoiceId());

        $xeroErrorLogs = XeroErrorLog::whereIn('id', $xeroErrorLogIDs)->get();
        foreach ($xeroErrorLogs as $xeroErrorLog) {
            $xeroErrorLog->delete();
        }

        foreach ($items as $index => $item) {
            if ($item->is_hotlotz_own_stock == 'N') {
                $item->bill_id = $sellerInvoice->getInvoices()[0]->getInvoiceId();
                $item->save();
            }
        }

        if (!is_null($sellerLatestInvoice) && $sellerLatestInvoice->status == $invoiceStatus) {
            $customerBill = $sellerLatestInvoice;
            $customerBill->xero_invoice_data = $sellerInvoice->getInvoices()[0]->__toString();
            // $customerBill->active = $auction_id == null ? 1 : 0;
            $customerBill->save();
        } else {
            if ($sellerType != null) {
                $invoiceType = $sellerType;
            } else {
                $invoiceType = $type;
            }
            $customerBill = $this->xeroControlRepository->createCustomerInvoice($customer_id, $sellerInvoice->getInvoices()[0]->getInvoiceId(), $invoiceType, 'bill', $sellerInvoice->getInvoices()[0]->__toString(), $auction_id);
        }

        foreach ($items as $index => $item) {
            $this->xeroControlRepository->createCustomerInvoiceItem($customerBill->id, $item->id, $prices[$index]);
        }

        return $sellerInvoice;
    }

    public function buyerAuctionXeroInvoice($customer_id, array $items, array $prices, $ref, $auction_id, $date = null)
    {
        $customer = Customer::findOrFail($customer_id);

        $buyerLineItems = [];

        $buyerAuctionInvoice = $customer->invoices()->where('auction_id', $auction_id)->where('invoice_type', 'auction')->where('type', 'invoice')->latest()->first();

        $xeroErrorLogIDs = [];
        $uniqueKey = Str::random(10);

        foreach ($items as $index => $item) {
            $oldItemFilter = null;

            if ($buyerAuctionInvoice) {
                $oldItemFilter = $buyerAuctionInvoice->items->where('item_id', $item->id)->where('cancel_sale', 0)->first();
            }

            if ($oldItemFilter == null) {
                $getBuyerLineItems = $this->xeroControlRepository->getBuyerLineItems($prices[$index], $item, $customer, $auction_id);
                foreach ($getBuyerLineItems as $lineItem) {
                    $buyerLineItems[] = $lineItem;
                }
            }

            $invoiceID = null;
            if ($buyerAuctionInvoice) {
                $invoiceID = $buyerAuctionInvoice->invoice_id;
            }
            $xeroErrorLog = XeroErrorLog::create([
                'seller_id' => $item->customer_id,
                'buyer_id' => $item->buyer_id,
                'item_id' => $item->id,
                'amount' => $item->sold_price,
                'type' => 'auction invoice',
                'invoice_id' => $invoiceID,
                'unique_key' => $uniqueKey
            ]);
            $xeroErrorLogIDs[] = $xeroErrorLog->id;
        }
        $buyerContactID = $this->xeroContactRepository->createOrGetContact($customer_id);

        $buyerContact = $this->xeroControlRepository->setXeroContact($buyerContactID);

        $buyerInvoice = $this->setBuyerInvoice($buyerContact, $buyerLineItems, Invoice::STATUS_AUTHORISED, $buyerAuctionInvoice, $ref, LineAmountTypes::INCLUSIVE, $date);

        \Log::channel('xeroLog')->info('Success Buyer Invoice '.$buyerInvoice->getInvoices()[0]->getInvoiceId());

        $xeroErrorLogs = XeroErrorLog::whereIn('id', $xeroErrorLogIDs)->get();
        foreach ($xeroErrorLogs as $xeroErrorLog) {
            $xeroErrorLog->delete();
        }

        foreach ($items as $index => $item) {
            $item->invoice_id = $buyerInvoice->getInvoices()[0]->getInvoiceId();
            $item->save();
        }

        if (!is_null($buyerAuctionInvoice) && $buyerAuctionInvoice->status == 'Awaiting Payment') {
            $customerInvoice = $buyerAuctionInvoice;
            $customerInvoice->xero_invoice_data = $buyerInvoice->getInvoices()[0]->__toString();
            // $customerInvoice->active = 0;
            $customerInvoice->save();
        } else {
            $customerInvoice = $this->xeroControlRepository->createCustomerInvoice($customer_id, $buyerInvoice->getInvoices()[0]->getInvoiceId(), 'auction', 'invoice', $buyerInvoice->getInvoices()[0]->__toString(), $auction_id);
        }

        foreach ($items as $index => $item) {
            $this->xeroControlRepository->createCustomerInvoiceItem($customerInvoice->id, $item->id, $prices[$index]);
        }

        return $buyerInvoice;
    }

    protected function setSellerInvoice($sellerContact, $sellerLineItems, $sellerLatestInvoice, $status = Invoice::STATUS_AUTHORISED, $ref = 'Hotlotz', $type = null, $date = null)
    {
        $this->refreshCredential();
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;

        $invoice_status = 'Awaiting Payment';

        if ($date) {
            $date = new Carbon($date);
            $dueDate = new Carbon($date);
        } else {
            $date = Carbon::now();
            $dueDate = Carbon::now();
        }

        if ($type != 'private' && $type != 'marketplace' && !is_null($sellerLatestInvoice) && $sellerLatestInvoice->status == $invoice_status) {
            $xeroInvoice = $sellerLatestInvoice->invoice();

            foreach ($xeroInvoice->LineItems as $lineItem) {
                $sellerLineItems[] = $lineItem;
            }

            /**
             * Bill update DNP to RTP change
             * Hold on at ( 19.8.21 )
             */
            // $setInvoiceNumber = str_replace('RTP', 'DNP', $xeroInvoice->InvoiceNumber);
            // $invoice->setInvoiceNumber($setInvoiceNumber)

            $invoice
                ->setDueDate($dueDate->addWeeks(3)->format('Y-m-d'))
                ->setLineItems($sellerLineItems)
                ->setStatus($status)
                ->setBrandingThemeId($this->inclusiveBrandingThemeId)
                ->setLineAmountTypes(LineAmountTypes::INCLUSIVE);

            return $apiInstance->updateInvoice($xeroTenantId, $sellerLatestInvoice->invoice_id, $invoice);
        } else {
            $invoice->setReference($ref)
                ->setInvoiceNumber($this->setSettlementInvoiceNumber($type))
                ->setDate($date->format('Y-m-d'))
                ->setDueDate($dueDate->addWeeks(3)->format('Y-m-d'))
                ->setContact($sellerContact)
                ->setLineItems($sellerLineItems)
                ->setStatus($status)
                ->setType(Invoice::TYPE_ACCPAY)
                ->setBrandingThemeId($this->inclusiveBrandingThemeId)
                ->setLineAmountTypes(LineAmountTypes::INCLUSIVE);

            return $apiInstance->createInvoices($xeroTenantId, $invoice, true);
        }
    }

    public function setBuyerInvoice($buyerContact, $buyerLineItems, $status = Invoice::STATUS_AUTHORISED, $buyerInvoice = null, $ref, $invoiceType = LineAmountTypes::EXCLUSIVE, $date = null)
    {
        $this->refreshCredential();
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        if ($invoiceType == LineAmountTypes::EXCLUSIVE) {
            $brandingThemeId = $this->exclusiveBrandingThemeId;
        } else {
            $brandingThemeId = $this->inclusiveBrandingThemeId;
        }

        $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;

        if ($date) {
            $date = new Carbon($date);
            $dueDate = new Carbon($date);
        } else {
            $date = Carbon::now();
            $dueDate = Carbon::now();
        }

        if (!is_null($buyerInvoice) && $buyerInvoice->status == 'Awaiting Payment') {
            $xeroInvoice = $buyerInvoice->invoice();

            foreach ($xeroInvoice->LineItems as $lineItem) {
                $buyerLineItems[] = $lineItem;
            }

            $invoice
                ->setDueDate($dueDate->add('3 days')->format('Y-m-d'))
                ->setLineItems($buyerLineItems)
                ->setBrandingThemeId($brandingThemeId)
                ->setLineAmountTypes($invoiceType);

            return $apiInstance->updateInvoice($xeroTenantId, $buyerInvoice->invoice_id, $invoice);
        } else {
            $invoice->setReference($ref)
                ->setDate($date->format('Y-m-d'))
                ->setDueDate($dueDate->add('3 days')->format('Y-m-d'))
                ->setContact($buyerContact)
                ->setLineItems($buyerLineItems)
                ->setStatus($status)
                ->setType(Invoice::TYPE_ACCREC)
                ->setBrandingThemeId($brandingThemeId)
                ->setLineAmountTypes($invoiceType);

            return $apiInstance->createInvoices($xeroTenantId, $invoice, true);
        }
    }

    public function withdrawInvoice($payload, $itemForm = 'marketplace')
    {
        $this->refreshCredential();
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $item = Item::findOrFail($payload);
        if ($item->fee_structure->withdrawal_fee_setting == 1) {
            $fee = str_replace('$', '', $item->fee_structure->withdrawal_fee);

            $seller = $item->customer;
            $taxType = TaxType::ZERORATEDOUTPUT;
            // if ($seller->buyer_gst_status == 1) {
            //     $taxType = "OUTPUTY23";
            // }

            $lineItems = [];
            $lineItems[] = $this->xeroControlRepository->withdrawFee($fee, $item, $itemForm = 'marketplace', $taxType);

            $withdrawCustomerInvoice = $item->customer->invoices()->where('invoice_type', 'withdraw')->where('type', 'invoice')->latest()->first();

            $uniqueKey = Str::random(10);
            $invoiceID = null;
            if ($withdrawCustomerInvoice) {
                $invoiceID = $withdrawCustomerInvoice->invoice_id;
            }
            $xeroErrorLog = XeroErrorLog::create([
                'seller_id' => $item->customer_id,
                'buyer_id' => $item->customer_id,
                'item_id' => $item->id,
                'amount' => $fee,
                'type' => 'withdraw',
                'invoice_id' => $invoiceID,
                'unique_key' => $uniqueKey
            ]);

            $sellerContactID = $this->xeroContactRepository->createOrGetContact($item->customer_id);

            $sellerContact = $this->xeroControlRepository->setXeroContact($sellerContactID);

            $withdrawInvoice = $this->setBuyerInvoice($sellerContact, $lineItems, Invoice::STATUS_AUTHORISED, $withdrawCustomerInvoice, 'Withdraw Invoice');

            $xeroErrorLog->delete();

            $customerInvoice = $this->xeroControlRepository->createCustomerInvoice($item->customer_id, $withdrawInvoice->getInvoices()[0]->getInvoiceId(), 'withdraw', 'invoice', $withdrawInvoice->getInvoices()[0]->__toString());

            $xeroItem = XeroItem::where('item_code', 'Withdrawal Fee')->first();

            $this->xeroControlRepository->createCustomerInvoiceItem($customerInvoice->id, $xeroItem->id, $fee, 'wiithdraw');

            return "Create Invoice for Withdraw: " . $withdrawInvoice->getInvoices()[0]->getInvoiceId();
        } else {
            return 'Item is not on withdraw fee setting '. $payload;
        }
    }

    public function createPrivateSaleInvoice($payload)
    {
        $item = Item::findOrFail($payload['item_id']);

        $ref = "Private Sale Invoice ";

        $customer = Customer::findOrFail($payload['buyer_id']);

        $buyerLineItems = $this->xeroControlRepository->getPrivateBuyerLineItems($payload['sold_price_inclusive_gst'], $item, $customer, $payload['buyer_premiun'], $payload['type']);

        $buyerPrivateInvoice = $customer->invoices()->where('auction_id', null)->where('invoice_type', 'private')->where('type', 'invoice')->latest()->first();

        $invoiceID = null;
        $uniqueKey = Str::random(10);

        if ($buyerPrivateInvoice) {
            $invoiceID = $buyerPrivateInvoice->invoice_id;
        }
        $xeroErrorLog = XeroErrorLog::create([
            'seller_id' => $item->customer_id,
            'buyer_id' => $item->buyer_id,
            'item_id' => $item->id,
            'amount' => $item->sold_price,
            'type' => 'private invoice',
            'invoice_id' => $invoiceID,
            'unique_key' => $uniqueKey
        ]);

        $buyerContactID = $this->xeroContactRepository->createOrGetContact($payload['buyer_id']);

        $buyerContact = $this->xeroControlRepository->setXeroContact($buyerContactID);

        $buyerInvoice = $this->setBuyerInvoice($buyerContact, $buyerLineItems, Invoice::STATUS_AUTHORISED, null, $ref, LineAmountTypes::INCLUSIVE);

        \Log::channel('xeroLog')->info('Success Buyer Invoice '.$buyerInvoice->getInvoices()[0]->getInvoiceId());

        $xeroErrorLog->delete();

        $customerInvoice = $this->xeroControlRepository->createCustomerInvoice($payload['buyer_id'], $buyerInvoice->getInvoices()[0]->getInvoiceId(), 'private', 'invoice', $buyerInvoice->getInvoices()[0]->__toString());

        $this->xeroControlRepository->createCustomerInvoiceItem($customerInvoice->id, $payload['item_id'], $payload['sold_price_exclusive_gst'], 'private');

        $item->invoice_id = $buyerInvoice->getInvoices()[0]->getInvoiceId();
        $item->save();

        if ($item->is_hotlotz_own_stock == 'N') {
            $this->sellerXeroInvoice($item->customer_id, [$item], [$payload['sold_price_inclusive_gst']], $ref, $auction_id = null, $type = 'private', 'private');
        }

        return "Done Create Private Invoice";
    }

    public function createPrivateSaleInvoiceMultiItems($multiPayload)
    {
        $buyerLineItems = [];
        $xeroErrorLogIDs = [];

        $ref = "Private Sale Invoice ";

        foreach ($multiPayload['items'] as $payload) {
            $item = Item::findOrFail($payload['item_id']);

            $customer = Customer::findOrFail($payload['buyer_id']);

            $buyerLineItemsData = $this->xeroControlRepository->getPrivateBuyerLineItems($payload['sold_price_inclusive_gst'], $item, $customer, $payload['buyer_premiun'], $payload['type']);
            foreach ($buyerLineItemsData as $value) {
                array_push($buyerLineItems, $value);
            }
            $buyerPrivateInvoice = $customer->invoices()->where('auction_id', null)->where('invoice_type', 'private')->where('type', 'invoice')->latest()->first();

            $invoiceID = null;
            $uniqueKey = Str::random(10);

            if ($buyerPrivateInvoice) {
                $invoiceID = $buyerPrivateInvoice->invoice_id;
            }

            $xeroErrorLog = XeroErrorLog::create([
                'seller_id' => $item->customer_id,
                'buyer_id' => $item->buyer_id,
                'item_id' => $item->id,
                'amount' => $item->sold_price,
                'type' => 'private invoice',
                'invoice_id' => $invoiceID,
                'unique_key' => $uniqueKey
            ]);

            $xeroErrorLogIDs[] = $xeroErrorLog->id;
        }
        $buyerContactID = $this->xeroContactRepository->createOrGetContact($customer->id);

        $buyerContact = $this->xeroControlRepository->setXeroContact($buyerContactID);

        $buyerInvoice = $this->setBuyerInvoice($buyerContact, $buyerLineItems, Invoice::STATUS_AUTHORISED, null, $ref, LineAmountTypes::INCLUSIVE);

        \Log::channel('xeroLog')->info('Success Buyer Invoice '.$buyerInvoice->getInvoices()[0]->getInvoiceId());

        $xeroErrorLogs = XeroErrorLog::whereIn('id', $xeroErrorLogIDs)->get();
        foreach ($xeroErrorLogs as $xeroErrorLog) {
            $xeroErrorLog->delete();
        }

        $customerInvoice = $this->xeroControlRepository->createCustomerInvoice($customer->id, $buyerInvoice->getInvoices()[0]->getInvoiceId(), 'private', 'invoice', $buyerInvoice->getInvoices()[0]->__toString());

        $sellerObject = [];
        foreach ($multiPayload['items'] as $payload) {
            $item = Item::findOrFail($payload['item_id']);

            $this->xeroControlRepository->createCustomerInvoiceItem($customerInvoice->id, $payload['item_id'], $payload['sold_price_exclusive_gst'], 'private');

            $item->invoice_id = $buyerInvoice->getInvoices()[0]->getInvoiceId();
            $item->save();

            if ($item->is_hotlotz_own_stock == 'N') {
                $sellerObject[$item->customer_id]['items'][] = $item;
                $sellerObject[$item->customer_id]['prices'][] = $payload['sold_price_inclusive_gst'];
            }
        }

        foreach ($sellerObject as $index => $data) {
            $this->sellerXeroInvoice($index, $data['items'], $data['prices'], $ref, $auction_id = null, $type = 'private', 'private');
        }
        return "Done Create Private Invoice";
    }

    protected function updateInventory($price, $tax, $xero_product_id, $item_code)
    {
        $this->refreshCredential();
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $updateSalePrice['price'] = $price;
        $updateSalePrice['tax'] = $tax;
        $updateSalePrice['xero_product_id'] = $xero_product_id;
        $updateSalePrice['item_code'] = $item_code;

        $this->xeroProductRepository->updateSalePrice($updateSalePrice, $xeroTenantId, $apiInstance);
        \Log::channel('xeroLog')->info('Success Update Xero Inventory '.$xero_product_id);
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
}
