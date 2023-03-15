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

class ItemLifecycleInAuction implements ShouldQueue
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
    public $item_id, $auction_id;
    public function __construct($item_id, $auction_id)
    {
        $this->item_id = $item_id;
        $this->auction_id = $auction_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ItemRepository $itemRepository)
    {
        \Log::channel('lifecycleLog')->info('======= Start - ItemLifecycleInAuction Job =======');

        DB::beginTransaction();
        try {
            $item_id = $this->item_id;
            \Log::channel('lifecycleLog')->info('Item Id : '.$item_id);

            $auction_id = $this->auction_id;
            \Log::channel('lifecycleLog')->info('Auction Id : '.$auction_id);

            $item = Item::find($item_id);
            \Log::channel('lifecycleLog')->info('isset item : '.isset($item));

            $itemlifecycle = ItemLifecycle::where('item_id', $item_id)->where('reference_id', $auction_id)->first();
            \Log::channel('lifecycleLog')->info('isset itemlifecycle : '.isset($itemlifecycle));

            $item_data = [];
            $today = date('Y-m-d H:i:s');

            if ($itemlifecycle != null) {

                \Log::channel('lifecycleLog')->info('ItemLifecycle Id : '.$itemlifecycle->id);

                if ($itemlifecycle->type == 'auction') {

                    $item_data['status'] = Item::_IN_AUCTION_;
                    $item_data['lifecycle_status'] = Item::_AUCTION_;

                    //Lot Create Event
                    $not_exist_lot = AuctionItem::whereNull('deleted_at')
                            ->where('item_id', $item_id)
                            ->where('auction_id', $itemlifecycle->reference_id)
                            ->whereNull('lot_id')
                            ->count();

                    $auction = Auction::find($itemlifecycle->reference_id);

                    if ($not_exist_lot > 0 && isset($auction) && $auction->is_closed != 'Y' && $auction->sr_auction_id != null) {
                        \Log::channel('lifecycleLog')->info('dispatch LotCreateJob');
                        LotCreateJob::dispatch($item_id, $auction->id, $auction->sr_auction_id);
                    }
                }
                \Log::channel('lifecycleLog')->info('item_data : '.print_r($item_data, true));

                if (count($item_data) > 0) {
                    \Log::channel('lifecycleLog')->info('update Item in lifecycle '.$item_data['lifecycle_status']);
                    $result = $itemRepository->update($item_id, $item_data, true, $item_data['lifecycle_status']);
                }

                DB::commit();
                \Log::channel('lifecycleLog')->info('DB commit');
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::channel('lifecycleLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('lifecycleLog')->info('======= End - ItemLifecycleInAuction Job =======');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('lifecycleLog')->error('======= Failed - ItemLifecycleInAuction Job '. $this->item_id .'=======');
    }
}
