<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use App\Events\Auction\AuctionPublishEvent;

class CheckAuctionsPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:check_auctions_publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Auction is "Published" at GAP';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - CheckAuctionsPublish =======');
        \Log::channel('getAuctionsStatusLog')->info(date('Y-m-d H:i:s').' ======= Start - CheckAuctionsPublish =======');

        try{
            $auctions = Auction::where('is_published','!=','Y')
                        ->whereNotNull('sr_auction_id')
                        ->pluck('id');

            \Log::channel('getAuctionsStatusLog')->info('count of no published auctions '.count($auctions));

            if($auctions && count($auctions)>0){
                foreach ($auctions as $key => $auction_id) {
                    \Log::channel('getAuctionsStatusLog')->info('call AuctionPublishEvent');
                    event( new AuctionPublishEvent($auction_id) );
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - CheckAuctionsPublish - " . $e->getMessage());
            \Log::channel('getAuctionsStatusLog')->error("ERROR - CheckAuctionsPublish - " . $e->getMessage());
        }

        \Log::channel('getAuctionsStatusLog')->info(date('Y-m-d H:i:s').' ======= End - CheckAuctionsPublish =======');
        $this->info(date('Y-m-d H:i:s').' ======= End - CheckAuctionsPublish =======');
    }
}
