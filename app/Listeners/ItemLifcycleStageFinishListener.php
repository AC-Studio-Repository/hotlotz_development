<?php

namespace App\Listeners;

use App\Events\ItemLifcycleStageFinishEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Helpers\NHelpers;

class ItemLifcycleStageFinishListener
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
     * @param  ItemLifcycleStageFinishEvent  $event
     * @return void
     */
    public function handle(ItemLifcycleStageFinishEvent $event)
    {
        \Log::channel('lifecycleLog')->info('Start - ItemLifcycleStageFinishEvent');

        $itemlifecycle_id = $event->itemlifecycle_id;
        \Log::channel('lifecycleLog')->info('itemlifecycle_id : '.print_r($itemlifecycle_id, true));

        ItemLifecycle::where('id',$itemlifecycle_id)->update(['action'=>ItemLifecycle::_FINISHED_] + NHelpers::updated_at_by());
        
        \Log::channel('lifecycleLog')->info('End - ItemLifcycleStageFinishEvent');
    }
}
