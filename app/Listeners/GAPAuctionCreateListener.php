<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\GAPAuctionCreateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Auction\Models\Auction;
use App\Modules\Auction\Http\Repositories\AuctionRepository;
use App\Helpers\NHelpers;
use DB;

class GAPAuctionCreateListener implements ShouldQueue
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
     * @param  GAPAuctionCreateEvent  $event
     * @return void
     */
    public function handle(GAPAuctionCreateEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GAPAuctionCreateEvent');

        try {
            // \Log::channel('gapLog')->info('Auction : '.print_r($event->auction, true));

            $result = Auction::createAuction($event->auction);
            // \Log::channel('gapLog')->info('API Response : '.print_r($result, true));

            if (isset($result['auction_id'])) {
                $sr_auction_data = Auction::getAuctionById($result['auction_id']);
                // \Log::channel('gapLog')->info('sr_auction_data : '.print_r($sr_auction_data, true));

                $auction_data = [
                    'status'=>'Awaiting approval',
                    'is_approved'=>'N',
                    'is_published'=>'N',
                    'sr_auction_id'=>$result['auction_id'],
                    'sr_reference'=>$sr_auction_data['auction_reference'],
                    'sr_auction_data'=>$sr_auction_data,
                ];

                $this->auctionRepository->update($event->auction->id, $auction_data, true);

                // DB::table('gap_errors')->where('reference_id', $event->auction->id)->where('module', 'auction')->where('action', 'create')->delete();

                \Log::channel('gapLog')->info('Success : GAP Auction Created Successfully!');
            }
            // if (isset($result['error'])) {
            //     // \Log::channel('gapLog')->info('Error : '.$result['error']);
            //     $err_data = [
            //         'module'=>'auction',
            //         'reference_id'=>$event->auction->id,
            //         'action'=>'create',
            //         'error_name'=>'Error for Create GAP Auction',
            //         'error'=>$result['error'],
            //         'description'=>'Exception when calling AuctionApi->createAuction',
            //     ];
            //     DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
            // }
        } catch (\Exception $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - GAPAuctionCreateEvent');
    }
}
