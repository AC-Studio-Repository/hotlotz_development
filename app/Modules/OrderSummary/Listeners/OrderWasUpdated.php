<?php

namespace App\Modules\OrderSummary\Listeners;

use App\Modules\OrderSummary\Events\OrderWasUpdated as OrderWasUpdatedEvent;

class OrderWasUpdated
{
    public function handle(OrderWasUpdatedEvent $event)
    {
        \Log::info('OrderWasUpdated : '. $event->order->id);
    }
}
