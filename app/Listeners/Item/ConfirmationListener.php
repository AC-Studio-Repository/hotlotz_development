<?php

namespace App\Listeners\Item;

use App\Events\Item\ConfirmationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConfirmationListener
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
     * @param  ConfirmationEvent  $event
     * @return void
     */
    public function handle(ConfirmationEvent $event)
    {
        \Log::channel('emailLog')->info('Start - ConfirmationEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\Confirmation($customer));
        }

        \Log::channel('emailLog')->info('Start - ConfirmationEvent');
    }
}
