<?php

namespace App\Listeners\Client;

use App\Events\Client\SaleroomCustomerRegisterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class SaleroomCustomerRegisterListener
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
     * @param  SaleroomCustomerRegisterEvent  $event
     * @return void
     */
    public function handle(SaleroomCustomerRegisterEvent $event)
    {
        \Log::channel('emailLog')->info('Start - SaleroomCustomerRegisterEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $auction_name = $event->auction_name;
        \Log::channel('emailLog')->info('auction_name : '.$auction_name);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\User\SaleroomCustomerRegister($customer, $auction_name, null));
        }

        \Log::channel('emailLog')->info('End - SaleroomCustomerRegisterEvent');
    }
}
