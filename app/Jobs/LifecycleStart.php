<?php

namespace App\Jobs;

use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Auction\Models\Auction;
use App\Events\ItemHistoryEvent;
use App\Jobs\LotCreateJob;
use App\Helpers\NHelpers;
use DB;

class LifecycleStart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    // public $tries = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $itemRepository;
    public $item_id;
    public function __construct($item_id)
    {
        $this->item_id = $item_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ItemRepository $itemRepository)
    {
        \Log::channel('lifecycleLog')->info('======= Start - LifecycleStart Job =======');

        DB::beginTransaction();
        try {
            $item_id = $this->item_id;
            \Log::channel('lifecycleLog')->info('Item Id : '.$item_id);

            $item = Item::find($item_id);
            \Log::channel('lifecycleLog')->info('isset item : '.isset($item));

            $itemlifecycle = ItemLifecycle::where('item_id', $item_id)->first();
            \Log::channel('lifecycleLog')->info('isset itemlifecycle : '.isset($itemlifecycle));
            \Log::channel('lifecycleLog')->info('is_null itemlifecycle : '.is_null($itemlifecycle));
            \Log::channel('lifecycleLog')->info('is empty itemlifecycle : '.empty($itemlifecycle));

            $item_data = [];
            $item_history_status = '';
            $today = date('Y-m-d H:i:s');

            \Log::channel('lifecycleLog')->info('if condition itemlifecycle : '.($itemlifecycle != null && isset($itemlifecycle) && !is_null($itemlifecycle) && !empty($itemlifecycle) && ($itemlifecycle->action === null || $itemlifecycle->action === '' || !in_array($itemlifecycle->action, [ItemLifecycle::_PROCESSING_, ItemLifecycle::_FINISHED_, ItemLifecycle::_SKIPPED_]) ) ) );

            if ($itemlifecycle && $itemlifecycle != null && isset($itemlifecycle) && !is_null($itemlifecycle) && !empty($itemlifecycle) && ($itemlifecycle->action === null || $itemlifecycle->action === '' || !in_array($itemlifecycle->action, [ItemLifecycle::_PROCESSING_, ItemLifecycle::_FINISHED_, ItemLifecycle::_SKIPPED_]) ) ) {

                \Log::channel('lifecycleLog')->info('ItemLifecycle Id : '.$itemlifecycle->id);

                if ($itemlifecycle->type == 'auction') {
                    $item_history_status = Item::_AUCTION_;

                    $item_data['status'] = Item::_PENDING_IN_AUCTION_;
                    $item_data['lifecycle_status'] = Item::_AUCTION_;
                    $item_data['entered_auction1_date'] = $today;
                }
                if ($itemlifecycle->type == 'marketplace') {
                    $item_history_status = Item::_MARKETPLACE_;

                    $item_data['status'] = Item::_IN_MARKETPLACE_;
                    $item_data['lifecycle_status'] = Item::_MARKETPLACE_;
                    $item_data['entered_marketplace_date'] = $today;

                }
                if ($itemlifecycle->type == 'clearance') {
                    $item_history_status = Item::_CLEARANCE_;

                    $item_data['status'] = Item::_IN_MARKETPLACE_;
                    $item_data['lifecycle_status'] = Item::_CLEARANCE_;
                    $item_data['entered_clearance_date'] = $today;

                }
                if ($itemlifecycle->type == 'storage') {
                    $item_history_status = Item::_STORAGE_;

                    $item_data['status'] = Item::_STORAGE_;
                    $item_data['lifecycle_status'] = Item::_STORAGE_;
                    $item_data['storage_date'] = $today;
                    $item_data['tag'] = 'in_storage';

                }
                \Log::channel('lifecycleLog')->info('item_data : '.print_r($item_data, true));


                \Log::channel('lifecycleLog')->info('update ItemLifecycle in lifecycle '.$item_data['lifecycle_status']);
                ItemLifecycle::where('id', $itemlifecycle->id)->update(['action'=>ItemLifecycle::_PROCESSING_, 'entered_date'=>$today] + NHelpers::updated_at_by());

                if (count($item_data) > 0) {
                    \Log::channel('lifecycleLog')->info('update Item in lifecycle '.$item_data['lifecycle_status']);
                    $result = $itemRepository->update($item_id, $item_data, true, $item_data['lifecycle_status']);
                }

                //for Email Schedule
                if ($item_history_status != '') {
                    $item_history = [
                        'item_id' => $item_id,
                        'customer_id' => $item->customer_id,
                        'auction_id' => $itemlifecycle->reference_id,
                        'item_lifecycle_id' => $itemlifecycle->id,
                        'price' => $itemlifecycle->price,
                        'type' => 'lifecycle',
                        'status' => $item_history_status,
                        'entered_date' => $today,
                    ];
                    \Log::channel('lifecycleLog')->info('call ItemHistoryEvent');
                    event( new ItemHistoryEvent($item_history) );
                }

                DB::commit();
                \Log::channel('lifecycleLog')->info('DB commit');
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::channel('lifecycleLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('lifecycleLog')->info('======= End - LifecycleStart Job =======');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('lifecycleLog')->error('======= Failed - LifecycleStart Job '. $this->item_id .'=======');
    }
}
