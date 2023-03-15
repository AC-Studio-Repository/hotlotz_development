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
use Illuminate\Support\Facades\Artisan;

class CheckLot implements ShouldQueue
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
    // protected $itemRepository;
    protected $lot_id;
    public function __construct($lot_id)
    {
        $this->lot_id = $lot_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('checkAuctionLog')->info('======= Start - CheckLot Job =======');

        $lot_id = $this->lot_id;
        \Log::channel('checkAuctionLog')->info("lot_id : ".$lot_id);

        try {
            $lot = AuctionItem::where('lot_id', $lot_id)->first();

            if (isset($lot)) {
                if ($lot->status == Item::_SOLD_ || $lot->status == Item::_UNSOLD_) {
                    \Log::channel('checkAuctionLog')->info("Item_".$lot->item_id." is already ".$lot->status);
                    \Log::channel('checkAuctionLog')->info("Lot_".$lot_id." Job is deleted");
                    $this->job->delete();
                } else {
                    try {
                        \Log::channel('checkAuctionLog')->info('called gap:checklot Command');
                        Artisan::call('gap:checklot', ['lot_id'=>$lot_id]);
                    } catch (\Exception $e2) {
                        \Log::channel('checkAuctionLog')->info("Artisan call gap:checklot error : ".$e2->getMessage());
                        
                        // if ($this->attempts() > $this->tries) {
                        //     \Log::channel('checkAuctionLog')->info("extended 10 seconds");
                        //     $this->release(10);
                        // }
                    }
                }
            } else {
                \Log::channel('checkAuctionLog')->info("Lot_".$lot_id." is not exist in your system");
                $this->job->delete();
            }
        } catch (\Exception $e) {
            \Log::channel('checkAuctionLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('checkAuctionLog')->info('======= End - CheckLot Job =======');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('CheckLotLog')->error('======= Failed - CheckLot Job '. $this->lot_id .'=======');
    }
}
