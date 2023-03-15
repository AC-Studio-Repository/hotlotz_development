<?php

namespace App\Listeners\Auction;

use App\Exceptions\QueueFailReport;
use App\Events\Auction\AuctionPublishEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Auction\Models\Auction;
use App\Modules\Auction\Http\Repositories\AuctionRepository;

class AuctionPublishListener
{
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
     * @param  AuctionPublishEvent  $event
     * @return void
     */
    public function handle(AuctionPublishEvent $event)
    {
        \Log::channel('getAuctionsStatusLog')->info('Start - AuctionPublishEvent');

        try {
            $auction_id = $event->auction_id;
            \Log::channel('getAuctionsStatusLog')->info('Auction Id : '.$auction_id);

            $auction = Auction::find($auction_id);

            if($auction != null && $auction->sr_auction_id != null){
                \Log::channel('getAuctionsStatusLog')->info('SR Auction Id : '.$auction->sr_auction_id);
                $result = Auction::getAuctionById($auction->sr_auction_id);

                if( isset($result['error']) ) {
                    \Log::channel('getAuctionsStatusLog')->error('GAP Error - This Auction is not exist in GAP Toolbox');
                    // throw new QueueFailReport($result['error']);
                }                

                if ( isset($result) && !isset($result['error'])) {
                    \Log::channel('getAuctionsStatusLog')->info('Auction is published : '.$result['is_published']);
                    $auction_data = [];
                    if ($result['is_published']) {
                        $auction_data['status'] = 'Published';
                        $auction_data['is_approved'] = 'Y';
                        $auction_data['is_published'] = 'Y';
                        
                        \Log::channel('getAuctionsStatusLog')->info('auction_data : '.print_r($auction_data, true));
                        $this->auctionRepository->update($auction_id, $auction_data, true);
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::channel('getAuctionsStatusLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('getAuctionsStatusLog')->info('End - AuctionPublishEvent');
    }
}
