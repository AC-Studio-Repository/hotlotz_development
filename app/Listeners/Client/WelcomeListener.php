<?php

namespace App\Listeners\Client;

use App\Events\Client\WelcomeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class WelcomeListener
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
     * @param  WelcomeEvent  $event
     * @return void
     */
    public function handle(WelcomeEvent $event)
    {
        \Log::channel('emailLog')->info('Start - WelcomeEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\User\Welcome($customer));
        }

        \Log::channel('emailLog')->info('Start - WelcomeEvent');
    }
}
