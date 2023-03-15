<?php

namespace App\Listeners;

use App\Events\AuctionUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuctionUpdatedListener
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
     * @param  AuctionUpdatedEvent  $event
     * @return void
     */
    public function handle(AuctionUpdatedEvent $event)
    {
        \Log::info('Auction "'.$event->auction->title.'" is updated.');
    }
}
