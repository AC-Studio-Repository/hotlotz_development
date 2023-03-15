<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ItemLifcycleNextStageChangeEvent;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Jobs\LotCreateJob;
use App\Helpers\NHelpers;
use App\Events\ItemHistoryEvent;

class ItemLifcycleNextStageChangeListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 10;
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
     * @param  ItemLifcycleNextStageChangeEvent  $event
     * @return void
     */
    public function handle(ItemLifcycleNextStageChangeEvent $event)
    {
        \Log::channel('lifecycleLog')->info('Start - ItemLifcycleNextStageChangeEvent');

        try {

            \Log::channel('lifecycleLog')->info('Item Id : '.$event->item_id);
            $item = Item::find($event->item_id);

            \Log::channel('lifecycleLog')->info('Old itemlifecycle_id : '.$event->old_item_lifecycle_id);
            $old_item_lifecycle_id = $event->old_item_lifecycle_id;

            if( $item && isset($item) && $item != null && !is_null($item) && !empty($item) && !in_array($item->status,[Item::_SOLD_,Item::_PAID_,Item::_SETTLED_]) ){
                $today = date('Y-m-d H:i:s');

                $newitemlifecycle = ItemLifecycle::where('item_id',$event->item_id)->whereNull('status')->whereNull('action')->first();

                if( $newitemlifecycle && isset($newitemlifecycle) && $newitemlifecycle != null && !is_null($newitemlifecycle) && !empty($newitemlifecycle) && $old_item_lifecycle_id != $newitemlifecycle->id ){

                    \Log::channel('lifecycleLog')->info('New ItemLifecycle Id : '.$newitemlifecycle->id);
                    
                    ItemLifecycle::where('id',$old_item_lifecycle_id)->update(['action'=>ItemLifecycle::_FINISHED_] + NHelpers::updated_at_by());

                    ItemLifecycle::where('id', $newitemlifecycle->id)->update(['action'=>ItemLifecycle::_PROCESSING_, 'entered_date'=>$today] + NHelpers::updated_at_by());


                    $item_data = [];
                    if ($newitemlifecycle->type == 'auction' && $newitemlifecycle->reference_id != null) {
                        $item_data['status'] = Item::_PENDING_IN_AUCTION_;
                        $item_data['lifecycle_status'] = Item::_AUCTION_;
                        $item_data['entered_auction2_date'] = $today;

                        // $auction2 = Auction::find($newitemlifecycle->reference_id);

                        // if (isset($auction2) && $auction2->is_closed != 'Y') {
                        //     //Lot Create Event
                        //     $not_exist_lot = AuctionItem::whereNull('deleted_at')
                        //             ->where('item_id', $event->item_id)
                        //             ->where('auction_id', $newitemlifecycle->reference_id)
                        //             ->whereNull('lot_id')
                        //             ->count();

                        //     if ($not_exist_lot > 0) {
                        //         if ($auction2->sr_auction_id != null) {
                        //             \Log::channel('lifecycleLog')->info('dispatch LotCreateJob');
                        //             LotCreateJob::dispatch($event->item_id, $auction2->id, $auction2->sr_auction_id);
                        //         }
                        //     }
                        // }
                    }

                    if ($newitemlifecycle->type == 'marketplace') {
                        $item_data['status'] = Item::_IN_MARKETPLACE_;
                        $item_data['lifecycle_status'] = Item::_MARKETPLACE_;
                        $item_data['entered_marketplace_date'] = $today;
                    }
                    if ($newitemlifecycle->type == 'clearance') {
                        $item_data['status'] = Item::_IN_MARKETPLACE_;
                        $item_data['lifecycle_status'] = Item::_CLEARANCE_;
                        $item_data['entered_clearance_date'] = $today;
                    }
                    if ($newitemlifecycle->type == 'storage') {
                        $item_data['status'] = Item::_UNSOLD_;
                        $item_data['lifecycle_status'] = Item::_STORAGE_;
                        $item_data['storage_date'] = $today;
                        $item_data['tag'] = 'in_storage';
                    }
                    \Log::channel('lifecycleLog')->info('item_data : '.print_r($item_data, true));

                    if (count($item_data) > 0) {
                        $result = $this->itemRepository->update($event->item_id, $item_data, true, $item_data['lifecycle_status']);

                        $item_history = [
                            'item_id' => $event->item_id,
                            'customer_id' => $item->customer_id,
                            'buyer_id' => null,
                            'auction_id' => $newitemlifecycle->reference_id,
                            'item_lifecycle_id' => $newitemlifecycle->id,
                            'price' => $newitemlifecycle->price,
                            'type' => 'lifecycle',
                            'status' => $item_data['lifecycle_status'],
                            'entered_date' => $today,
                        ];

                        \Log::channel('lifecycleLog')->info('call ItemHistoryEvent');
                        event( new ItemHistoryEvent($item_history) );
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('lifecycleLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('lifecycleLog')->info('End - ItemLifcycleNextStageChangeEvent');
    }
}
