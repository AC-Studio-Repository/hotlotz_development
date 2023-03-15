<?php

namespace App\Listeners\Item;

use App\Events\Item\RecentlyConsignedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Item\Models\Item;

class RecentlyConsignedListener implements ShouldQueue
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
     * @param  RecentlyConsignedEvent  $event
     * @return void
     */
    public function handle(RecentlyConsignedEvent $event)
    {
        \Log::info('Start - RecentlyConsignedEvent');
        Item::find($event->item_id)->update(['recently_consigned'=> $event->status]);
        \Log::info('End - RecentlyConsignedEvent');
    }
}
