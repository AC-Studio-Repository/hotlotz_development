<?php

namespace App\Listeners\Client;

use Illuminate\Support\Facades\Mail;
use App\Events\Client\PrivateInvoiceEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrivateInvoiceListener
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
     * @param  PrivateInvoiceEvent  $event
     * @return void
     */
    public function handle(PrivateInvoiceEvent $event)
    {
        \Log::channel('emailLog')->info('Start - PrivateInvoiceEvent');

        $customer_id = $event->customer_id;
        $customerInvoice = $event->customerInvoice;

        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\User\PrivateInvoice());
        }

        \Log::channel('emailLog')->info('End - PrivateInvoiceEvent');
    }
}