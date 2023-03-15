<?php

namespace App\Listeners;

use App\Events\CustomerUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerUpdatedListener
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
     * @param  CustomerUpdatedEvent  $event
     * @return void
     */
    public function handle(CustomerUpdatedEvent $event)
    {
        \Log::info('Customer "'.$event->customer->fullname.'" is updated.');
    }
}
