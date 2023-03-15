<?php

namespace App\Listeners\Client;

use App\Events\Client\AccountActivateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AccountActivateListener
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
     * @param  AccountActivateEvent  $event
     * @return void
     */
    public function handle(AccountActivateEvent $event)
    {
        \Log::channel('emailLog')->info('Start - AccountActivateEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\Activate($customer));
        }

        \Log::channel('emailLog')->info('Start - AccountActivateEvent');
    }
}
