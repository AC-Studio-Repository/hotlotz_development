<?php

namespace App\Listeners\Client;

use Illuminate\Support\Facades\Mail;
use App\Events\Client\AuctionInvoiceEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuctionInvoiceListener
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
     * @param  SettlementEvent  $event
     * @return void
     */
    public function handle(AuctionInvoiceEvent $event)
    {
        \Log::channel('emailLog')->info('Start - AuctionInvoiceEvent');

        $customer_id = $event->customer_id;
        $customerInvoice = $event->customerInvoice;

        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\User\AuctionInvoice($customerInvoice));
        }

        \Log::channel('emailLog')->info('End - AuctionInvoiceEvent');
    }
}
