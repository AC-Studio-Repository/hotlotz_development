<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\GAPAuctionUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Auction\Models\Auction;
use App\Modules\Auction\Http\Repositories\AuctionRepository;
use App\Helpers\NHelpers;
use DB;

class GAPAuctionUpdateListener implements ShouldQueue
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
    public function __construct(AuctionRepository $auctionRepository)
    {
        $this->auctionRepository = $auctionRepository;
    }

    /**
     * Handle the event.
     *
     * @param  GAPAuctionUpdateEvent  $event
     * @return void
     */
    public function handle(GAPAuctionUpdateEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GAPAuctionUpdateEvent');

        try {
            // \Log::channel('gapLog')->info('Auction : '.print_r($event->auction, true));

            $result = Auction::updateAuction($event->auction);
            // \Log::channel('gapLog')->info('updateAuction : '.print_r($result, true));


            if (isset($result['error'])) {
                \Log::channel('gapLog')->info('Error : '.$result['error']);
                $err_data = [
                    'module'=>'auction',
                    'reference_id'=>$event->auction->id,
                    'action'=>'update',
                    'error_name'=>'Error for Update GAP Auction',
                    'error'=>$result['error'],
                    'description'=>'Exception when calling AuctionApi->updateAuction',
                ];
                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
            } else {
                DB::table('gap_errors')->where('reference_id', $event->auction->id)->where('module', 'auction')->where('action', 'update')->delete();

                $sr_auction_data = Auction::getAuctionById($event->auction->sr_auction_id);
                \Log::channel('gapLog')->info('sr_auction_data : '.print_r($sr_auction_data, true));

                $auction_data = [
                    'sr_reference'=>$sr_auction_data['auction_reference'],
                    'sr_auction_data'=>$sr_auction_data,
                ];
                $this->auctionRepository->update($event->auction->id, $auction_data, true);

                \Log::channel('gapLog')->info('Success : GAP Auction Updated Successfully!');
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - GAPAuctionUpdateEvent');
    }
}
