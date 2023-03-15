<?php

namespace App\Jobs;

use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemImage;
use App\Jobs\AddImageUrlToLotBotJob;
use App\Helpers\NHelpers;
use DB;

class LotBotCreateJob implements ShouldQueue
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
    public $item_id, $from_auction_id, $to_auction_id;
    public function __construct($item_id, $from_auction_id, $to_auction_id)
    {
        $this->item_id = $item_id;
        $this->from_auction_id = $from_auction_id;
        $this->to_auction_id = $to_auction_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('gapLog')->info('Start - LotBotCreateJob');

        try {
            $item_id = $this->item_id;
            \Log::channel('gapLog')->info('Item Id : '.$item_id);

            $from_auction_id = $this->from_auction_id;
            // \Log::channel('gapLog')->info('From AuctionId : '.$from_auction_id);

            $to_auction_id = $this->to_auction_id;
            // \Log::channel('gapLog')->info('From AuctionId : '.$to_auction_id);

            $auction = Auction::find($this->to_auction_id);
            if(isset($auction)){
                // \Log::channel('gapLog')->info('SR AuctionId : '.$auction->sr_auction_id);

                $item = Item::find($item_id);

                if (isset($item)) {

                    ## New Logic for Lot Number
                    $auctionitem = AuctionItem::whereNull('deleted_at')->where('item_id', $item_id)->where('auction_id', $from_auction_id)->first();

                    // $lot_number = rand();
                    if (isset($auctionitem)) {
                        $lot_number = $auctionitem->sequence_number ?? $auctionitem->id;
                        \Log::channel('gapLog')->info('Lot Number : '.$lot_number);

                        $result = Item::createLot($item, $from_auction_id, $auction->sr_auction_id, $lot_number);

                        if (isset($result['lot_id'])) {
                            \Log::channel('gapLog')->info('LotId : '.$result['lot_id']);
                            \Log::channel('gapLog')->info('Success - LotBotCreateJob : '.$item_id);

                            // ### Add Images to Lot
                            \Log::channel('gapLog')->info('dispatch AddImageUrlToLotBotJob');
                            AddImageUrlToLotBotJob::dispatch($item_id, $result['lot_id']);
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            // throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - LotBotCreateJob');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('gapLog')->error('======= Failed - LotBotCreateJob '. $this->item_id .'=======');
    }
}
