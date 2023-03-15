<?php

namespace App\Modules\OrderSummary\Listeners;

use App\Modules\OrderSummary\Events\OrderWasCreated as OrderWasCreatedEvent;

class OrderWasCreated
{
    public function handle(OrderWasCreatedEvent $event)
    {
        \Log::info('OrderWasCreated : '. $event->order->id);
    }
}
