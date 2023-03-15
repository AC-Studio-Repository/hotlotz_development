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
use App\Modules\Item\Models\AuctionItem;
use App\Helpers\NHelpers;

class LotsReorderBKK implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $inputs, $sr_auction_id, $buyers_premium;
    public function __construct($inputs, $sr_auction_id, $buyers_premium)
    {
        $this->inputs = $inputs;
        $this->sr_auction_id = $sr_auction_id;
        $this->buyers_premium = $buyers_premium;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('lotReorderingLog')->info('======= Start - LotsReorder Job =======');

        $inputs = $this->inputs;

        $sr_auction_id = $this->sr_auction_id;
        \Log::channel('lotReorderingLog')->info("sr_auction_id : ".$sr_auction_id);

        $buyers_premium = $this->buyers_premium;
        \Log::channel('lotReorderingLog')->info("buyers_premium : ".$buyers_premium);

        try {
            \Log::channel('lotReorderingLog')->info('Start - getLotsByAuctionId');
            $gap_lots = Item::getLotsByAuctionId($sr_auction_id);
            \Log::channel('lotReorderingLog')->info('End - getLotsByAuctionId');

            
            if (isset($gap_lots['error'])) {
                \Log::channel('lotReorderingLog')->error('getLotsByAuctionId GAP ERROR : ');
                if ($this->attempts() > $this->tries) {
                    \Log::channel('lotReorderingLog')->info("extended 10 seconds");
                    $this->release(10);
                }
            }

            if (isset($gap_lots)) {

                $auctionitem_lots = [];
                $temp_lots = [];
                $sortable_lots = [];


                \Log::channel('lotReorderingLog')->info('Start - Temp Sort');
                foreach ($inputs['lot_id'] as $key2 => $lot_id) {
                    $auctionitem_lots[$inputs['auction_item'][$key2]] = $key2 + 1;

                    if (isset($gap_lots)) {
                        foreach ($gap_lots as $key => $gap_lot) {
                            if ($lot_id === $gap_lot['lot_id']) {
                                $gap_lot['sequence_number'] = $key2 + 1;
                                $gap_lot['lot_number'] = $gap_lot['lot_number'].'T';
                                $temp_lots[] = $gap_lot;
                            }
                        }
                    }
                }
                \Log::channel('lotReorderingLog')->info('End - Temp Sort');
                
                \Log::channel('lotReorderingLog')->info('Start - auctionitem_lots update : ');
                foreach ($auctionitem_lots as $id => $sequence_number) {
                    AuctionItem::where('id', $id)->update(['lot_number'=>$sequence_number, 'sequence_number'=>$sequence_number] + NHelpers::updated_at_by());
                }
                \Log::channel('lotReorderingLog')->info('End - auctionitem_lots update : ');


                if (isset($temp_lots)) {
                    \Log::channel('lotReorderingLog')->info('Start - GAP Temp Sort Lot : ');
                    $temp_lots_arr = array_chunk($temp_lots, 50);
                    foreach ($temp_lots_arr as $key => $tmplots) {
                        $temporary_lots['Lots'] = $tmplots;
                        try {

                            $result = Item::updateLots($temporary_lots);

                        } catch (\Exception $e2) {
                            \Log::channel('lotReorderingLog')->info('ERROR - GAP Temp Sort Lot : ');
                            if ($this->attempts() > $this->tries) {
                                \Log::channel('lotReorderingLog')->info('GAP Temp Sort Lot - extended 10 seconds');
                                $this->release(10);
                            }
                        }
                    }
                    \Log::channel('lotReorderingLog')->info('End - GAP Temp Sort Lot : ');
                }
                
                \Log::channel('lotReorderingLog')->info('Start - GAP Sort');
                foreach ($inputs['lot_id'] as $key2 => $lot_id) {
                    if (isset($gap_lots)) {
                        foreach ($gap_lots as $key => $gap_lot) {
                            if ($lot_id === $gap_lot['lot_id']) {
                                $gap_lot['sequence_number'] = $key2 + 1;
                                $gap_lot['lot_number'] = $gap_lot['sequence_number'];
                                $gap_lot['buyers_premium_percent'] = $buyers_premium;
                                $sortable_lots[] = $gap_lot;
                            }
                        }
                    }
                }
                \Log::channel('lotReorderingLog')->info('End - GAP Sort');


                if (isset($sortable_lots)) {
                    \Log::channel('lotReorderingLog')->info('Start - GAP Sort Lot : ');
                    $sortable_lots_arr = array_chunk($sortable_lots, 50);
                    foreach ($sortable_lots_arr as $key => $sortablelots) {
                        $sort_lots['Lots'] = $sortablelots;
                        try {
                            
                            $result2 = Item::updateLots($sort_lots);
                            
                        } catch (\Exception $e2) {
                            \Log::channel('lotReorderingLog')->info('ERROR - GAP Sort Lot : ');
                            if ($this->attempts() > $this->tries) {
                                \Log::channel('lotReorderingLog')->info('GAP Sort Lot - extended 10 seconds');
                                $this->release(10);
                            }
                        }
                        
                    }
                    \Log::channel('lotReorderingLog')->info('End - GAP Sort Lot : ');
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
        \Log::channel('lotReorderingLog')->error('======= Failed - LotsReorder Job '. $this->sr_auction_id .'=======');
    }
}
