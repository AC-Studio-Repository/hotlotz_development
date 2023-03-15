<?php

namespace App\Listeners;

use App\Events\ItemCreatedEvent;

class ItemCreatedListener
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
     * @param  ItemCreatedEvent  $event
     * @return void
     */
    public function handle(ItemCreatedEvent $event)
    {
        \Log::info('New Item is created.');
        \Log::info('New Item : '.$event->item);
    }
}
