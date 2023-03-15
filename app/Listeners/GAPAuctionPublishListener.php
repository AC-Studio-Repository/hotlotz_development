<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\GAPAuctionPublishEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Auction\Models\Auction;
use App\Helpers\NHelpers;
use DB;
use App\Modules\Auction\Http\Repositories\AuctionRepository;

class GAPAuctionPublishListener implements ShouldQueue
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
    protected $auctionRepository;
    public function __construct(AuctionRepository $auctionRepository){
        $this->auctionRepository = $auctionRepository;
    }

    /**
     * Handle the event.
     *
     * @param  GAPAuctionPublishEvent  $event
     * @return void
     */
    public function handle(GAPAuctionPublishEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GAPAuctionPublishEvent');
        try {
            \Log::channel('gapLog')->info("Auction : ". print_r($event->auction, true));

            $data = [
                'AuctionId' => $event->auction->sr_auction_id
            ];
            \Log::channel('gapLog')->info("AuctionID : ". print_r($data, true));

            $result = Auction::publishAuction($data);

            if(isset($result['error'])){
                \Log::channel('gapLog')->info('Error : '.$result['error']);
                $err_data = [
                    'module'=>'auction',
                    'reference_id'=>$event->auction->id,
                    'action'=>'publish',
                    'error_name'=>'Error for Publish GAP Auction',
                    'error'=>$result['error'],
                    'description'=>'Exception when calling AuctionApi->publishAuction',
                ];
                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
            }
            if( isset($result) && !isset($result['error']) ){
                $auction_data = [
                    'status' => 'Published',
                    'is_published' => 'Y',
                ];

                $auction_id = Auction::where('sr_auction_id',$event->auction_id)->first()->id;
                $this->auctionRepository->update($auction_id, $auction_data, true);

                DB::table('gap_errors')->where('reference_id',$event->auction->id)->where('module','auction')->where('action','publish')->delete();

                \Log::channel('gapLog')->info('Success : GAP Auction Pusblished Successfully!');
            }

        } catch(\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - GAPAuctionPublishEvent');
    }
}
