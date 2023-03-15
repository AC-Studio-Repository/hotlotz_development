<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use App\Events\Auction\AuctionApproveEvent;

class CheckAuctionsApprove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:check_auctions_approve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Auction is "Approved" at GAP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - CheckAuctionsApprove =======');
        \Log::channel('getAuctionsStatusLog')->info(date('Y-m-d H:i:s').' ======= Start - CheckAuctionsApprove =======');

        try{
            $auctions = Auction::where('is_approved','!=','Y')
                        ->whereNotNull('sr_auction_id')
                        ->pluck('id');

            \Log::channel('getAuctionsStatusLog')->info('count of no approved auctions '.count($auctions));

            if($auctions && count($auctions)>0){
                foreach ($auctions as $key => $auction_id) {
                    \Log::channel('getAuctionsStatusLog')->info('call AuctionApproveEvent');
                    event( new AuctionApproveEvent($auction_id) );
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - CheckAuctionsApprove - " . $e->getMessage());
            \Log::channel('getAuctionsStatusLog')->error("ERROR - CheckAuctionsApprove - " . $e->getMessage());
        }

        \Log::channel('getAuctionsStatusLog')->info(date('Y-m-d H:i:s').' ======= End - CheckAuctionsApprove =======');
        $this->info(date('Y-m-d H:i:s').' ======= End - CheckAuctionsApprove =======');
    }
}
