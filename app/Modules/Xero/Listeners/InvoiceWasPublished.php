<?php

namespace App\Modules\Xero\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Events\Client\AuctionInvoiceEvent;
use App\Events\Client\PrivateInvoiceEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Xero\Events\InvoiceWasPublished as InvoiceWasPublishedEvent;

class InvoiceWasPublished
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InvoiceWasPublishedEvent $event
     * @return void
     */
    public function handle(InvoiceWasPublishedEvent $event)
    {
        \Log::channel('xeroLog')->info('Start - InvoiceWasPublished Event');

        $invoice_id = $event->invoice_id; //invoice_id default = all
        $auction_id = $event->auction_id; //auction_id default = null
        $local = $event->local; //local default = local

        if($invoice_id != 'all') {
            $customerInvoice = CustomerInvoice::findOrFail($invoice_id);
            if($customerInvoice->type == 'invoice' && $customerInvoice->invoice_type == 'auction') {
                event(new AuctionInvoiceEvent($customerInvoice->customer_id, $customerInvoice));
            }
            if ($customerInvoice->type == 'invoice' && $customerInvoice->invoice_type == 'private') {
                event(new PrivateInvoiceEvent($customerInvoice->customer_id, $customerInvoice));
            }

        }

        if($auction_id != null) {
            $customerInvoices = CustomerInvoice::where('auction_id', $auction_id)->where('type', 'invoice')
            ->whereHas('customer', function($q) use ($local){
            if($local == 'local'){
                $q->where('country_of_residence', 702);
            }else{
                $q->where('country_of_residence', '!=', 702);
            }
            })->get();
            foreach ($customerInvoices as $customerInvoice) {
                event(new AuctionInvoiceEvent($customerInvoice->customer_id, $customerInvoice));
            }
        }

        \Log::channel('xeroLog')->info('invoice_id : '.$invoice_id);
        \Log::channel('xeroLog')->info('auction_id : '.$auction_id);
        \Log::channel('xeroLog')->info('local : '.$local);

        \Log::channel('xeroLog')->info('End - InvoiceWasPublished Event');
    }
}