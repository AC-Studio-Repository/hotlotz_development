<?php

namespace App\Listeners;

use App\Events\ItemUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Jobs\LifecycleStart;

class ItemUpdatedListener
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
     * @param  ItemUpdatedEvent  $event
     * @return void
     */
    public function handle(ItemUpdatedEvent $event)
    {
        \Log::info('Start - ItemUpdatedEvent');

        $item_id = $event->item_id;
        \Log::info('Item Id : '.$item_id);

        $event_status = $event->status;
        \Log::info('Event Status : '.$event_status);

        $item = Item::find($item_id);

        if($event_status == 'PermissionToSell' || $event_status == 'ApprovedCataloguing'){

            if($item && isset($item) && $item != null && !is_null($item) && !empty($item) && $item->is_cataloguing_approved === 'Y' && $item->permission_to_sell === 'Y' && $item->status === Item::_PENDING_){
                
                \Log::info('Item Update - dispatch LifecycleStart Job '.$item_id);
                LifecycleStart::dispatch($item_id);
            }
        }

        \Log::info('End - ItemUpdatedEvent');
    }
}
