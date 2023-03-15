<?php

namespace App\Jobs;

use Exception;
use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Helpers\NHelpers;

class LotsReorder implements ShouldQueue
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
    public $auction_id;
    public function __construct($auction_id)
    {
        $this->auction_id = $auction_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('lotReorderingLog')->info('======= Start - LotsReorder Job =======');

        $auction_id = $this->auction_id;
        \Log::channel('lotReorderingLog')->info("auction_id : ".$auction_id);

        try {
            $auction = Auction::find($auction_id);

            $auction_items = AuctionItem::where('auction_id',$auction_id)->orderBy('sequence_number')->pluck('sequence_number','lot_id')->all();
            \Log::channel('lotReorderingLog')->info("count of auction_items : ".count($auction_items));
            \Log::channel('lotReorderingLog')->info("auction_items : ".print_r($auction_items,true));

            if( isset($auction) && count($auction_items) > 0 ){

                \Log::channel('lotReorderingLog')->info('Start - getLotsByAuctionId');
                $gap_lots = Item::getLotsByAuctionId($auction->sr_auction_id);
                \Log::channel('lotReorderingLog')->info('End - getLotsByAuctionId');

                
                if (isset($gap_lots['error'])) {
                    \Log::channel('lotReorderingLog')->error('getLotsByAuctionId GAP ERROR : ');
                    if ($this->attempts() > $this->tries) {
                        \Log::channel('lotReorderingLog')->info("extended 10 seconds");
                        $this->release(10);
                    }
                }

                if (count($gap_lots) > 0) {

                    $temp_lots = [];
                    $sortable_lots = [];

                    \Log::channel('lotReorderingLog')->info('Start - Temp Sort Array');
                        foreach ($gap_lots as $key => $gap_lot) {
                            if ( array_key_exists($gap_lot['lot_id'], $auction_items) ) {
                                $gap_lot['sequence_number'] = $auction_items[$gap_lot['lot_id']];
                                $gap_lot['lot_number'] = $gap_lot['lot_number'].'T';
                                $gap_lot['buyers_premium_percent'] = $auction->buyers_premium;
                                $temp_lots[] = $gap_lot;
                            }
                        }
                    \Log::channel('lotReorderingLog')->info('End - Temp Sort Array');

                    if (isset($temp_lots)) {
                        \Log::channel('lotReorderingLog')->info('Start - GAP Temp Sort Lot : ');
                        $temp_lots_arr = array_chunk($temp_lots, 100);
                        foreach ($temp_lots_arr as $key => $tmplots) {
                            $temporary_lots['Lots'] = $tmplots;
                            // \Log::channel('lotReorderingLog')->info("temporary_lots : ".print_r($temporary_lots,true));
                            try {

                                $result = Item::updateLots($temporary_lots);

                            } catch (\Exception $e2) {
                                // \Log::channel('lotReorderingLog')->info('ERROR - GAP Temp Sort Lot : '.$e2->getMessage());
                                \Log::channel('lotReorderingLog')->error("ERROR - GAP Temp Sort Lot ('{$e2->getMessage()}')\n{$e2}\n");

                                // if ($this->attempts() > $this->tries) {
                                //     \Log::channel('lotReorderingLog')->info('GAP Temp Sort Lot - extended 10 seconds');
                                //     $this->release(10);
                                // }
                            }
                        }
                        \Log::channel('lotReorderingLog')->info('End - GAP Temp Sort Lot : ');
                    }
                
                    \Log::channel('lotReorderingLog')->info('Start - GAP Sort Array');
                        foreach ($gap_lots as $key => $gap_lot) {
                            if ( array_key_exists($gap_lot['lot_id'], $auction_items) ) {
                                $gap_lot['lot_number'] = $auction_items[$gap_lot['lot_id']];
                                $sortable_lots[] = $gap_lot;
                            }
                        }
                    \Log::channel('lotReorderingLog')->info('End - GAP Sort Array');


                    if (isset($sortable_lots)) {
                        \Log::channel('lotReorderingLog')->info('Start - GAP Sort Lot : ');
                        $sortable_lots_arr = array_chunk($sortable_lots, 100);
                        foreach ($sortable_lots_arr as $key => $sortablelots) {
                            $sort_lots['Lots'] = $sortablelots;
                            // \Log::channel('lotReorderingLog')->info("sort_lots : ".print_r($sort_lots,true));
                            try {                                
                                $result2 = Item::updateLots($sort_lots);
                                
                            } catch (\Exception $e2) {
                                \Log::channel('lotReorderingLog')->error("ERROR - GAP Sort Lot ('{$e2->getMessage()}')\n{$e2}\n");

                                // if ($this->attempts() > $this->tries) {
                                //     \Log::channel('lotReorderingLog')->info('GAP Sort Lot - extended 10 seconds');
                                //     $this->release(10);
                                // }
                            }
                            
                        }
                        \Log::channel('lotReorderingLog')->info('End - GAP Sort Lot : ');
                    }

                }
            }

        } catch (Exception $e) {
            \Log::channel('lotReorderingLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('lotReorderingLog')->info('======= End - LotsReorder Job =======');
    }

    public function failed(Exception $exception)
    {
        \Log::channel('lotReorderingLog')->error('======= Failed - LotsReorder Job =======');
        \Log::channel('lotReorderingLog')->error('======= Failed - auction_id '. $this->auction_id .'=======');
    }
}
