<?php

namespace App\Listeners;

use App\Events\ItemHistoryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\ItemHistory;
use App\Modules\Item\Http\Repositories\ItemRepository;

class ItemHistoryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $itemRepository;
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ItemHistoryEvent  $event
     * @return void
     */
    public function handle(ItemHistoryEvent $event)
    {
        \Log::info('Start - ItemHistoryEvent');

        $item_history = $event->item_history;
        \Log::info('item_history Item Id : '.print_r($item_history['item_id'],true));
        // \Log::info('item_history : '.print_r($item_history,true));

        $check = ItemHistory::where('item_id', $item_history['item_id'])
                ->where('item_lifecycle_id', $item_history['item_lifecycle_id'])
                ->where('type', $item_history['type'])
                ->where('status', $item_history['status'])
                ->count();
        \Log::info('check exist item_history : '.$check);       

        if($check <= 0){
            $result = ItemHistory::create($item_history);
        }

        \Log::info('End - ItemHistoryEvent');
    }
}
