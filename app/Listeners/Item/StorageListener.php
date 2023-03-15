<?php

namespace App\Listeners\Item;

use App\Events\Item\StorageEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class StorageListener
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
     * @param  StorageEvent  $event
     * @return void
     */
    public function handle(StorageEvent $event)
    {
        \Log::channel('storageFeeReminderEmailLog')->info('Start - StorageEvent');

        $customer_id = $event->customer_id;
        \Log::channel('storageFeeReminderEmailLog')->info('customer_id : '.$customer_id);

        $items = $event->items;
        \Log::channel('storageFeeReminderEmailLog')->info('count of items : '.count($items));

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\Storage($items));
        }

        \Log::channel('storageFeeReminderEmailLog')->info('Start - StorageEvent');
    }
}
