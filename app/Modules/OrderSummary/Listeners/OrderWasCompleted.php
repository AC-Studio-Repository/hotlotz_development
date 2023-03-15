<?php

namespace App\Modules\OrderSummary\Listeners;

use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Log;
use App\Modules\OrderSummary\Models\OrderSummary as Order;
use App\Modules\OrderSummary\Events\OrderWasCompleted as OrderWasCompletedEvent;

class OrderWasCompleted
{
    public function handle(OrderWasCompletedEvent $event)
    {
        \Log::info('Start OrderWasCompleted Event');
        if($event->item->invoice_id){
            $relatedItems = Item::where('invoice_id', $event->item->invoice_id)->count();
            $dispatchItems = Item::where('invoice_id', $event->item->invoice_id)->where('tag','dispatched')->count();
            if($relatedItems == $dispatchItems){
                $order = Order::where('invoice_id', $event->item->invoice_id)->first();
                $order->status = 'complete';
                $order->save();
            }
        }
        \Log::info('End OrderWasCompleted Event');

    }
}
