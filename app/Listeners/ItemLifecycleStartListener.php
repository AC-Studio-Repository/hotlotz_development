<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\ItemLifecycleStartEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Helpers\NHelpers;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Events\ItemHistoryEvent;


class ItemLifecycleStartListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 100;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $itemRepository;
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ItemLifecycleStartEvent $event)
    {
        \Log::channel('lifecycleLog')->info('Start - ItemLifecycleStartEvent');

        try {
            $itemlifecycle_id = $event->itemlifecycle_id;
            \Log::channel('lifecycleLog')->info('itemlifecycle_id : '.print_r($itemlifecycle_id, true));

            $item_id = $event->item_id;
            \Log::channel('lifecycleLog')->info('item_id : '.print_r($item_id, true));

            $itemlifecycle = $event->itemlifecycle;
            \Log::channel('lifecycleLog')->info('itemlifecycle : '.print_r($itemlifecycle, true));

            $lifecycle_status = $event->lifecycle_status;
            \Log::channel('lifecycleLog')->info('lifecycle_status : '.print_r($lifecycle_status, true));

            $today = date('Y-m-d H:i:s');


            ItemLifecycle::where('id', $itemlifecycle_id)->update(['action'=>ItemLifecycle::_PROCESSING_, 'entered_date'=>$today] + NHelpers::updated_at_by());

            $item_data = [];
            $item_data['lifecycle_status'] = $lifecycle_status;

            if ($lifecycle_status == Item::_AUCTION_) {
                $item_data['status'] = Item::_IN_AUCTION_;
                $item_data['entered_auction1_date'] = $today;
            }
            if ($lifecycle_status == Item::_MARKETPLACE_) {
                $item_data['status'] = Item::_IN_MARKETPLACE_;
                $item_data['entered_marketplace_date'] = $today;
            }
            if ($lifecycle_status == Item::_CLEARANCE_) {
                $item_data['status'] = Item::_IN_MARKETPLACE_;
                $item_data['entered_clearance_date'] = $today;
            }
            if ($lifecycle_status == Item::_STORAGE_) {
                $item_data['status'] = Item::_UNSOLD_;
                $item_data['storage_date'] = $today;
                $item_data['tag'] = 'in_storage';
            }
            \Log::channel('lifecycleLog')->info('item_data : '.print_r($item_data, true));

            $result = $this->itemRepository->update($item_id, $item_data, true, $lifecycle_status);

            if (count($event->email_data) > 0) {
                \Log::channel('lifecycleLog')->info('call ItemHistoryEvent');
                event( new ItemHistoryEvent($event->email_data) );
            }
        } catch (\Exception $e) {
            \Log::channel('lifecycleLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('lifecycleLog')->info('End - ItemLifecycleStartEvent');
    }

    public function failed(\Exception $e)
    {
        \Log::channel('lifecycleLog')->info('error : '.$e);
    }
}
