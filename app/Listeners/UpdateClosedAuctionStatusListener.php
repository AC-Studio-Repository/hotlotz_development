<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\UpdateClosedAuctionStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Auction\Models\Auction;
use App\Modules\Auction\Http\Repositories\AuctionRepository;

class UpdateClosedAuctionStatusListener
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
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
     * @param  UpdateClosedAuctionStatusEvent  $event
     * @return void
     */
    public function handle(UpdateClosedAuctionStatusEvent $event)
    {
        \Log::info('Start - UpdateClosedAuctionStatusEvent');

        try {
            $auction_id = $event->auction_id;
            \Log::info('Auction Id : '.$auction_id);

            $auction = Auction::find($auction_id);

            if($auction != null && $auction->sr_auction_id != null){

                $gap_auction = Auction::getAuctionById($auction->sr_auction_id);

                if( isset($gap_auction) && isset($gap_auction['error']) ){
                    \Log::info($auction->sr_auction_id.'_Auction is not exist in GAP Toolbox');
                    $auction_data['is_exist_gap_toolbox'] = 'N';
                    $this->auctionRepository->update($auction_id, $auction_data, true);
                }

                if( isset($gap_auction) && !isset($gap_auction['error']) ){
                    \Log::info('GAP Auction Status : '.$gap_auction['auction_status']);
                    $auction_data = [];

                    if ($gap_auction['auction_status'] != null && !in_array($auction->status, ['AwaitingSubmission', 'Submitted', 'ChecksInProgress', 'ReadyToInvoice', 'Invoiced'])) {
                        
                        // $auction_data['is_submitted'] = 'Y';
                        // $auction_data['status'] = 'Submitted';
                        $auction_data['status'] = $gap_auction['auction_status'];

                        \Log::info('auction_data : '.print_r($auction_data, true));
                        $this->auctionRepository->update($auction_id, $auction_data, true);
                    }
                }

            }else{
                \Log::info($auction_id.'_Auction is not exist in Our System');
            }

        } catch (\Exception $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::info('End - UpdateClosedAuctionStatusEvent');
    }

    public function failed(UpdateClosedAuctionStatusEvent $event, \Exception $exception)
    {
        \Log::error('======= Failed - CheckAuctionStatus Listener : '. $event->auction_id .'=======');
    }
}
