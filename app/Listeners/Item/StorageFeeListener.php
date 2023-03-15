<?php

namespace App\Listeners\Item;

use App\Events\Item\StorageFeeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class StorageFeeListener
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
     * @param  StorageFeeEvent  $event
     * @return void
     */
    public function handle(StorageFeeEvent $event)
    {
        \Log::channel('storageFeeReminderEmailLog')->info('Start - StorageFeeEvent');

        $customer_id = $event->customer_id;
        \Log::channel('storageFeeReminderEmailLog')->info('customer_id : '.$customer_id);

        $items = $event->items;
        \Log::channel('storageFeeReminderEmailLog')->info('count of Items : '.count($items));

        $type = $event->type;
        \Log::channel('storageFeeReminderEmailLog')->info('type : '.$type);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\StorageFee($items, $type));
        }

        \Log::channel('storageFeeReminderEmailLog')->info('Start - StorageFeeEvent');
    }
}
