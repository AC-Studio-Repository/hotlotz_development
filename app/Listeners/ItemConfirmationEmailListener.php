<?php

namespace App\Listeners;

use App\Events\ItemConfirmationEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ItemConfirmationEmailListener
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
     * @param  ItemConfirmationEmailEvent  $event
     * @return void
     */
    public function handle(ItemConfirmationEmailEvent $event)
    {
        \Log::channel('emailLog')->info('Start - ItemConfirmationEmailEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\Confirmation($customer));
        }

        \Log::channel('emailLog')->info('Start - ItemConfirmationEmailEvent');
    }
}
