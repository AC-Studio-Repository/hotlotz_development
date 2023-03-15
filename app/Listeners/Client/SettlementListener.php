<?php

namespace App\Listeners\Client;

use Illuminate\Support\Facades\Mail;
use App\Events\Client\SettlementEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;

class SettlementListener
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
    public function handle(SettlementEvent $event)
    {
        \Log::channel('emailLog')->info('Start - SettlementEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\User\Settlement($customer));
        }

        \Log::channel('emailLog')->info('Start - SettlementEvent');
    }
}
