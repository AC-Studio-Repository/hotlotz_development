<?php

namespace App\Modules\Xero\Webhook;

use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Events\Client\SettlementEvent;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use App\Modules\Customer\Models\Customer;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;
use XeroAPI\XeroPHP\Models\Accounting\TaxType;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\OrderSummary\Models\OrderSummary;
use App\Modules\Xero\Repositories\XeroProductRepository;

class Updated
{
    public $apiInstance;

    public function __construct(
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance,
        XeroProductRepository $xeroProductRepository
    ) {
        $this->xeroCredentials = $xeroCredentials;
        $this->apiInstance = $apiInstance;
        $this->xeroProductRepository = $xeroProductRepository;
    }

    public function invoiceUpdated($invoice)
    {
        \Log::channel('xeroLog')->info('Web Hook Invoice Update Called');
        try {
            $invoice_id = $invoice->getInvoiceId();
            $getType = $invoice->getType();
            $getInvoiceObject = $invoice->__toString();

            $customerInvoice = CustomerInvoice::where('invoice_id', $invoice_id)->first();

            if (isset($customerInvoice)) {
                if ($invoice->getStatus() !== 'DELETED' || $invoice->getStatus() !== 'DRAFT' || $invoice->getStatus() !== 'VOIDED') {
                    $customerInvoice->xero_invoice_data = $getInvoiceObject;
                    $customerInvoice->save();
                }

                if ($invoice->getStatus() == 'DELETED' || $invoice->getStatus() == 'DRAFT' || $invoice->getStatus() == 'VOIDED') {
                    $customerInvoice->delete();
                }
            }

            if ($getType == 'ACCREC' && isset($customerInvoice)) {
                \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Invoice');
                $items = Item::where('status', '!=', Item::_ITEM_RETURNED_)->where('invoice_id', $invoice_id)->get();

                if ($items->count() > 0 && $invoice->getType() == 'ACCREC' && $invoice->getStatus() == 'PAID') {
                    \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Get Status & Type ' . $invoice->getStatus() . ' ' .$invoice->getType());

                    if ($customerInvoice->invoice_type == 'auction') {
                        OrderSummary::where('invoice_id', $invoice_id)->update(array('status' => OrderSummary::PAID));
                    }
                    if ($customerInvoice->invoice_type == 'adhoc') {
                        OrderSummary::where('id', $customerInvoice->order_summary_id)->update(array('status' => OrderSummary::PAID));
                    }

                    foreach ($items as $item) {
                        \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Get Item Count ' . $items->count());
                        if ($item->status == Item::_SOLD_) {
                            $item->status = Item::_PAID_;
                            $item->paid_date = date('Y-m-d H:i:s');
                            if ($customerInvoice->invoice_type == 'auction') {
                                $item->updateRelatedItemStatus($item, Item::_PAID_, $item->buyer_id, $customerInvoice->invoice_type, $customerInvoice->auction_id);
                            }
                            \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Get Item Paid ' . $item->name);
                            $item->save();
                        }
                        if ($item->bill_id != null) {
                            $billItems = Item::where('status', '!=', Item::_ITEM_RETURNED_)->where('bill_id', $item->bill_id)->get();
                            $itemsPaidStaus = Item::where('bill_id', $item->bill_id)->whereIn('status', [ Item::_PAID_, Item::_DISPATCHED_])->count();
                            if ($billItems->count() > 0 && $billItems->count() == $itemsPaidStaus) {
                                $getBill = $this->getBill($item->bill_id);

                                $this->billUpdated($item->bill_id, $getBill, $customerInvoice);
                            }
                        }
                        if ($item->is_hotlotz_own_stock == 'Y') {
                            $buyer = Customer::findOrFail($customerInvoice->customer_id);

                            $taxType = TaxType::NONE;

                            if ($buyer->buyer_gst_status == 1) {
                                $taxType = "OUTPUTY23";
                            }

                            $customerInvoiceItem = $customerInvoice->items()->where('item_id', $item->id)->first();
                            $updateSalePrice['price'] = $customerInvoiceItem->price;
                            $updateSalePrice['tax'] = $taxType;
                            $updateSalePrice['xero_product_id'] = $item->xero_product_id;
                            $updateSalePrice['item_code'] = $item->item_number;
                            $this->xeroProductRepository->updateSalePrice($updateSalePrice, $this->xeroCredentials->getTenantId(), $this->apiInstance);
                        }
                    }
                    $customerInvoice->payment_processing = 0;
                    $customerInvoice->save();
                }
            }

            if ($getType == 'ACCPAY' && isset($customerInvoice)) {
                $billItems = Item::where('status', '!=', Item::_ITEM_RETURNED_)->where('bill_id', $invoice_id)->get();
                $itemsPaidStaus = Item::where('bill_id', $invoice_id)->whereIn('status', [ Item::_PAID_, Item::_DISPATCHED_])->count();
                if ($billItems->count() > 0 && $billItems->count() == $itemsPaidStaus) {
                    $this->billUpdated($invoice_id, $invoice, $customerInvoice);
                }
            }
        } catch (\Throwable $e) {
            $redis = Redis::connection();
            if (!$redis->exists(':webhook:invoices')) {
                $redis->set(':webhook:invoices', json_encode([$invoice->getInvoiceId()]));
            } else {
                $webhook_invoices = json_decode($redis->get(':webhook:invoices'));
                array_push($webhook_invoices, $invoice->getInvoiceId());
                $redis->set(':webhook:invoices', json_encode($webhook_invoices));
            }
            \Log::channel('xeroLog')->error('Web Hook Error');
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }

    public function billUpdated($invoice_id, $invoice, $customerInvoice)
    {
        \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Bill');
        $customerInvoice->invoice_url = null;
        $customerInvoice->save();

        $items = Item::where('status', '!=', Item::_ITEM_RETURNED_)->where('bill_id', $invoice_id)->get();
        $itemsPaidStaus = Item::where('bill_id', $invoice_id)->whereIn('status', [ Item::_PAID_, Item::_DISPATCHED_])->count();
        \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Get Item Paid Count ' . $itemsPaidStaus);
        if ($items->count() > 0 && $items->count() == $itemsPaidStaus) {
            if ($invoice->getStatus() == 'AUTHORISED') {
                \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Get Status & Type ' . $invoice->getStatus() . ' ' . $invoice->getType());
                if (str_starts_with($invoice->getInvoiceNumber(), 'DNP')) {
                    $invoiceUpdate = new Invoice;
                    $invoiceUpdate->setInvoiceNumber(str_replace('DNP', 'RTP', $invoice->getInvoiceNumber()));
                    $this->apiInstance->updateInvoice($this->xeroCredentials->getTenantId(), $invoice_id, $invoiceUpdate);
                    \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Bill move to RTP');
                    $this->addInvoiceHistory($invoice_id, 'All related invoices are fully paid.');
                } else {
                    \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Bill already moved to RTP');
                }
            }
            if ($invoice->getStatus() == 'PAID') {
                foreach ($items as $item) {
                    $item->status = Item::_SETTLED_;
                    $item->settled_date = date('Y-m-d H:i:s');
                    if ($customerInvoice->invoice_type == 'auction') {
                        $item->updateRelatedItemStatus($item, Item::_SETTLED_, $item->buyer_id, $customerInvoice->invoice_type, $customerInvoice->auction_id);
                    }
                    \Log::channel('xeroLog')->info($invoice_id .' Webhook Work Get Item Settled ' . $item->name);
                    $item->save();
                }
                // event(new SettlementEvent($customerInvoice->customer_id));
            }
        }
    }

    /**
     * Get invoice
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\Invoice
     */
    public function getBill($invoice_id)
    {
        \Log::channel('xeroLog')->info('Get Xero invoice function work');
        try {
            $result = $this->apiInstance->getInvoice($this->xeroCredentials->getTenantId(), $invoice_id);

            return $result->getInvoices()[0];
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            return null;
        }
    }

    public function addInvoiceHistory($invoiceId, $note)
    {
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
}
