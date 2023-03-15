<?php

namespace App\Listeners\Xero;

use App\Modules\Item\Models\Item;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use App\Modules\Xero\Accounting\Automate;
use App\Events\Xero\XeroPaidedInvoiceEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;
use XeroAPI\XeroPHP\Models\Accounting\TaxType;
use App\Events\Xero\ThirdPartyPaymentAlertEvent;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\OrderSummary\Models\OrderSummary;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\OrderSummary\Http\Repositories\OrderSummaryRepository;

class XeroPaidInvoiceListener
{
    public $apiInstance;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        XeroInvoiceRepository $xeroInvoiceRepository,
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance,
        OrderSummaryRepository $orderSummaryRepository,
        Automate $automate
    ) {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
        $this->xeroCredentials = $xeroCredentials;
        $this->apiInstance = $apiInstance;
        $this->orderSummaryRepository = $orderSummaryRepository;
        $this->automate = $automate;
    }

    public function init($arg)
    {
        $this->apiInstance = $arg;
    }

    /**
     * Handle the event.
     *
     * @param  XeroPaidedInvoiceEvent  $event
     * @return void
     */
    public function handle(XeroPaidedInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('Paid Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Paid Invoice Event '. print_r($event->payload, true) .'=======');

        $payload = $event->payload;

        try {
            $createPaymentinvoice = $this->automate->getInvoice($payload['invoice_id']);

            $this->xeroInvoiceRepository->createPayment($createPaymentinvoice, $payload['payment_intent']);
            $this->xeroInvoiceRepository->addInvoiceHistory($payload['invoice_id'], 'Stripe transaction with '.$payload['payment_intent']);
            $finalInvoice = $this->apiInstance->getInvoice($this->xeroCredentials->getTenantId(), $payload['invoice_id']);
            $customerInvoice = CustomerInvoice::where('invoice_id', $payload['invoice_id'])->first();
            if ($customerInvoice->order_summary_id != null) {
                $this->orderSummaryRepository->update($customerInvoice->order_summary_id, ['status' => OrderSummary::PAID]);
            }
            $customerInvoice->xero_invoice_data = $finalInvoice->getInvoices()[0]->__toString();
            $customerInvoice->save();
            if ($customerInvoice->invoice_type == 'auction' || $customerInvoice->invoice_type == 'private') {
                $orderSummary['invoice_id'] = $createPaymentinvoice->getInvoiceId();
                $orderSummary['customer_id'] = $customerInvoice->customer_id;
                $orderSummary['total'] = $createPaymentinvoice->getTotal();
                $orderSummary['from'] = $customerInvoice->invoice_type;
                $orderSummary['type'] = $payload['shipType'] == 'yes' ? 'ship' : 'pickup';
                $orderSummary['status'] = OrderSummary::PAID;

                if ($payload['shipType'] == 'yes') {
                    $orderSummary['address_id'] = $payload['addressId'];
                }
                $order = $this->orderSummaryRepository->create($orderSummary);

                foreach ($customerInvoice->items as $customerInvoiceItem) {
                    $item = $customerInvoiceItem->item;
                    if ($item->is_hotlotz_own_stock == 'N' && $item->bill_id != null) {
                        \Log::channel('xeroLog')->info($item->bill_id .' Paid Event Work Bill');

                        $items = Item::where('bill_id', $item->bill_id)->get();
                        $itemsPaidStaus =Item::where('bill_id', $item->bill_id)->whereIn('status', [ Item::_PAID_, Item::_DISPATCHED_])->count();
                        if ($items->count() > 0 && $items->count() == $itemsPaidStaus) {
                            $invoice = $this->automate->getInvoice($item->bill_id);

                            if ($invoice->getStatus() == 'SUBMITTED') {
                                \Log::channel('xeroLog')->info($payload['invoice_id'] .' Paid Event Work Get Status & Type ' . $invoice->getStatus() . ' ' . $invoice->getType());
                                $invoice2 = new Invoice;
                                $invoice2->setStatus('AUTHORISED');
                                $this->apiInstance->updateInvoice($this->xeroCredentials->getTenantId(), $item->bill_id, $invoice2);
                                \Log::channel('xeroLog')->info($item->bill_id .' Paid Event Work Bill move to awaiting payment');
                                $this->xeroInvoiceRepository->addInvoiceHistory($item->bill_id, 'This bill is complete payment');
                            }
                        }
                    }
                    if ($item->is_hotlotz_own_stock == 'Y') {
                        $this->xeroInvoiceRepository->updateInventory($customerInvoiceItem->price, "OUTPUTY23", $item->xero_product_id, $item->item_number);
                    }
                    if ($payload['shipType'] == 'yes') {
                        $item->delivery_requested = "Y";
                        $item->delivery_requested_date = date('Y-m-d H:i:s');
                        $item->save();
                    }
                    $order->items()->attach([$item->id]);
                }
            }
            $thirdPartyPaymentAlertPayload['customer_id'] = $payload['customer_id'];
            $thirdPartyPaymentAlertPayload['invoice_id'] = $createPaymentinvoice->getInvoiceId();
            $thirdPartyPaymentAlertPayload['invoice_number'] = $createPaymentinvoice->getInvoiceNumber();
            $thirdPartyPaymentAlertPayload['amount'] = $createPaymentinvoice->getTotal();
            $thirdPartyPaymentAlertPayload['payment_method'] = $payload['payment_type'];

            event(new ThirdPartyPaymentAlertEvent($thirdPartyPaymentAlertPayload));

            \Log::channel('xeroLog')->info('Paid Event Ended');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
