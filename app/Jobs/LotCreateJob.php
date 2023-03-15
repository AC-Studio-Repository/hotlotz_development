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
// use App\Events\GapAddImageUrlToLotEvent;
use App\Jobs\AddImageUrlToLotJob;
use App\Helpers\NHelpers;
use DB;

class LotCreateJob implements ShouldQueue
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
    public function __construct($item_id, $auction_id, $sr_auction_id)
    {
        $this->item_id = $item_id;
        $this->auction_id = $auction_id;
        $this->sr_auction_id = $sr_auction_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('gapLog')->info('Start - LotCreateJob');

        try {
            $item_id = $this->item_id;
            \Log::channel('gapLog')->info('Item Id : '.$item_id);

            $auction_id = $this->auction_id;
            \Log::channel('gapLog')->info('AuctionId : '.$auction_id);

            $auction = Auction::find($auction_id);
            if($auction && $auction != null) {
                \Log::channel('gapLog')->info('SR AuctionId : '.$auction->sr_auction_id);

                $item = Item::find($item_id);
                if ($item && $item != null) {
                    \Log::channel('gapLog')->info('Item status : '.$item->status);

                    ## New Logic for Lot Number
                    $auctionitem = AuctionItem::whereNull('deleted_at')->where('item_id', $item_id)->where('auction_id', $auction_id)->whereNull('lot_id')->first();

                    // $lot_number = rand();
                    if ($auctionitem && $auctionitem != null && !is_null($auctionitem) && !empty($auctionitem)) {
                        $lot_number = ($auctionitem->sequence_number!=null)?$auctionitem->sequence_number:$auctionitem->id;

                        \Log::channel('gapLog')->info('Lot Number : '.$lot_number);

                        $result = Item::createLot($item, $auction_id, $auction->sr_auction_id, $lot_number);
                        // \Log::channel('gapLog')->info('createLot result : '.print_r($result, true));

                        if (isset($result['lot_id']) && $result['lot_id'] != null) {
                            \Log::channel('gapLog')->info('LotId : '.$result['lot_id']);

                            $data = [
                                'lot_id'=>$result['lot_id'],
                                'lot_number'=>$lot_number,
                                'sequence_number'=>$lot_number,
                            ];

                            $lot = Item::getLot($result['lot_id']);
                            if (isset($lot)) {
                                $end_time_utc = NHelpers::changeJsonDateTimeToPhpDateTime($lot['end_time_utc']);
                                \Log::channel('gapLog')->info('end_time_utc : '.$end_time_utc);

                                $data['sr_lot_data'] = $lot;
                                $data['end_time_utc'] = $end_time_utc;
                            }

                            AuctionItem::where('item_id', $item_id)->where('auction_id', $auction_id)->update($data + NHelpers::updated_at_by());

                            // ### Add Images to Lot
                            \Log::channel('gapLog')->info('dispatch AddImageUrlToLotJob');
                            AddImageUrlToLotJob::dispatch($item_id, $result['lot_id'], $auction_id, $auction->sr_auction_id);

                            DB::table('gap_errors')->where('reference_id', $item_id)->where('module', 'lot')->where('action', 'create')->delete();
                        } else {
                            // \Log::channel('gapLog')->info('GAP Error : '.$result['error']);
                            $err_data = [
                                'module'=>'lot',
                                'reference_id'=>$item_id,
                                'action'=>'create',
                                'error_name'=>'Error for GAP createLot',
                                'error'=>$result['error'],
                                'description'=>'Exception when calling LotsApi->createLot',
                            ];
                            DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());

                            // if ($this->attempts() > $this->tries) {
                            //     \Log::channel('gapLog')->info('extended 10 seconds');
                            //     $this->release(10);
                            // }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - LotCreateJob');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('gapLog')->error('======= Failed - LotCreate Job '. $this->item_id .'=======');
    }
}
