<?php

namespace App\Jobs;

use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use App\Jobs\AddImageUrlToLotJob;
use App\Helpers\NHelpers;
use DB;

class LotUpdateJob implements ShouldQueue
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
    protected $item_id;
    protected $auction_id;
    protected $sr_auction_id;
    protected $lot_id;
    protected $lot_number;
    public function __construct($item_id, $auction_id, $sr_auction_id, $lot_id, $lot_number)
    {
        $this->item_id = $item_id;
        $this->auction_id = $auction_id;
        $this->sr_auction_id = $sr_auction_id;
        $this->lot_id = $lot_id;
        $this->lot_number = $lot_number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('gapLog')->info('Start - LotUpdateJob');

        try {
            $item_id = $this->item_id;
            \Log::channel('gapLog')->info('Item Id : '.$item_id);

            $auction_id = $this->auction_id;
            \Log::channel('gapLog')->info('AuctionId : '.$auction_id);

            $auction = Auction::find($auction_id);
            if($auction && $auction != null) {
                \Log::channel('gapLog')->info('SR AuctionId : '.$auction->sr_auction_id);

                $lot_id = $this->lot_id;
                \Log::channel('gapLog')->info('Lot ID : '.$lot_id);

                $lot_number = $this->lot_number;
                \Log::channel('gapLog')->info('Lot Number : '.$lot_number);

                $item = Item::find($item_id);
                if ($item && $item != null) {
                    \Log::channel('gapLog')->info('Item status : '.$item->status);

                    $result = Item::updateLot($item, $auction_id, $auction->sr_auction_id, $lot_id, $lot_number);
                    // \Log::channel('gapLog')->info('updateLot result : '.print_r($result, true));

                    if (isset($result['error'])) {
                        // \Log::channel('gapLog')->info('GAP Error : '.$result['error']);
                        $err_data = [
                            'module'=>'lot',
                            'reference_id'=>$item_id,
                            'action'=>'update',
                            'error_name'=>'Error for GAP updateLot',
                            'error'=>$result['error'],
                            'description'=>'Exception when calling LotsApi->updateLot',
                        ];
                        DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());

                        // if ($this->attempts() > $this->tries) {
                        //     \Log::channel('gapLog')->info('extended 10 seconds');
                        //     $this->release(10);
                        // }

                        throw new QueueFailReport($result['error']);
                    } else {
                        $lot = Item::getLot($lot_id);

                        if ($lot && $lot != null) {
                            $end_time_utc = NHelpers::changeJsonDateTimeToPhpDateTime($lot['end_time_utc']);
                            \Log::channel('gapLog')->info('end_time_utc : '.$end_time_utc);

                            $data = [
                                'sr_lot_data' => $lot,
                                'end_time_utc' => $end_time_utc,
                            ];
                            AuctionItem::where('item_id', $item_id)->where('lot_id', $lot_id)->update($data + NHelpers::updated_at_by());
                        }

                        // ### Add Images to Lot
                        \Log::channel('gapLog')->info('dispatch AddImageUrlToLotJob');
                        AddImageUrlToLotJob::dispatch($item_id, $lot_id, $auction_id, $auction->sr_auction_id);

                        DB::table('gap_errors')->where('reference_id', $item_id)->where('module', 'lot')->where('action', 'update')->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - LotUpdateJob');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('gapLog')->error('======= Failed - LotUpdate Job '. $this->item_id .'=======');
    }
}
