<?php

namespace App\Listeners;

use App\Events\AuctionCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuctionCreatedListener
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
     * @param  AuctionCreatedEvent  $event
     * @return void
     */
    public function handle(AuctionCreatedEvent $event)
    {
        \Log::info('New Auction is created.');
        \Log::info('Auction : '.$event->auction);
    }
}
