<?php

namespace App\Listeners\Item;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Item\DroppedOffItemReceivedEvent;

class DroppedOffItemReceivedListener
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
     * @param  DroppedOffItemReceivedEvent  $event
     * @return void
     */
    public function handle(DroppedOffItemReceivedEvent $event)
    {
        \Log::channel('emailLog')->info('Start - DroppedOffItemReceivedEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\DroppedOffItemReceived($customer));
        }

        \Log::channel('emailLog')->info('Start - DroppedOffItemReceivedEvent');
    }
}
