<?php

namespace App\Modules\OrderSummary\Providers;

use App\Modules\OrderSummary\Events\OrderWasCreated as OrderWasCreatedEvent;
use App\Modules\OrderSummary\Events\OrderWasUpdated as OrderWasUpdatedEvent;
use App\Modules\OrderSummary\Listeners\OrderWasCreated as OrderWasCreatedListner;
use App\Modules\OrderSummary\Listeners\OrderWasUpdated as OrderWasUpdatedListner;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Modules\OrderSummary\Events\OrderWasCompleted as OrderWasCompletedEvent;
use App\Modules\OrderSummary\Listeners\OrderWasCompleted as OrderWasCompletedListner;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderWasCreatedEvent::class => [
            OrderWasCreatedListner::class
        ],
        OrderWasUpdatedEvent::class => [
            OrderWasUpdatedListner::class
        ],
        OrderWasCompletedEvent::class => [
            OrderWasCompletedListner::class
        ]
    ];
}
