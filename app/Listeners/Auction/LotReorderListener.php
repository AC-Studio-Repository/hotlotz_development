<?php

namespace App\Listeners\Auction;

use App\Events\Auction\LotReorderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\LotsReorder;

class LotReorderListener
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
     * @param  LotReorderEvent  $event
     * @return void
     */
    public function handle(LotReorderEvent $event)
    {
        \Log::channel('lotReorderingLog')->info('Start - LotReorderEvent');

        $auction_id = $event->auction_id;
        \Log::channel('lotReorderingLog')->info('auction_id : '.$auction_id);

        \Log::channel('lotReorderingLog')->info('Start - Call LotsReorder Job');
        LotsReorder::dispatch($auction_id);

        \Log::channel('lotReorderingLog')->info('End - LotReorderEvent');
    }
}
