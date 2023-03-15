<?php

namespace App\Modules\Xero\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Xero\Events\BillWasPublished as BillWasPublishedEvent;
use App\Events\Client\SettlementEvent;

class BillWasPublished
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
     * @param  BillWasPublishedEvent $event
     * @return void
     */
    public function handle(BillWasPublishedEvent $event)
    {
        \Log::channel('xeroLog')->info('Start - BillWasPublishedEvent Event');

        $invoice_id = $event->invoice_id; //invoice_id default = all
        $auction_id = $event->auction_id; //auction_id default = null

        \Log::channel('xeroLog')->info('invoice_id : '.$invoice_id);
        \Log::channel('xeroLog')->info('auction_id : '.$auction_id);

        $customerInvoice = CustomerInvoice::find($invoice_id);
        event(new SettlementEvent($customerInvoice->customer_id));

        \Log::channel('xeroLog')->info('End - BillWasPublishedEvent Event');
    }
}
