<?php

namespace App\Listeners\Item;

use App\Events\Item\CancelSaleItemEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Modules\Item\Models\Item;

class CancelSaleItemListener
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
     * @param  CancelSaleItemEvent  $event
     * @return void
     */
    public function handle(CancelSaleItemEvent $event)
    {
        \Log::info('Start - CancelSaleItemEvent');

        // $item_id = $event->item_id;
        // \Log::info('item_id : '.$item_id);

        // $item = Item::find($item_id);
        // if (isset($item->customer)) {
        //     $customer = $item->customer;
        //     Mail::to($customer->email)
        //         ->send(new \App\Mail\Item\CancelSale($item));
        // }

        \Log::info('End - CancelSaleItemEvent');
    }
}
