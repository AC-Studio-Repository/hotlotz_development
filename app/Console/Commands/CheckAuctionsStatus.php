<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use App\Events\CheckAuctionStatusEvent;

class CheckAuctionsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:get_auctions_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Auction is "Approved" or "Published" at GAP';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - CheckAuctionsStatus =======');
        \Log::channel('getAuctionsStatusLog')->info(date('Y-m-d H:i:s').' ======= Start - CheckAuctionsStatus =======');

        try{
            $auctions = Auction::where('is_published','!=','Y')
                        ->whereNotNull('sr_auction_id')
                        ->pluck('id');

            \Log::channel('getAuctionsStatusLog')->info('count of auctions '.count($auctions));

            if($auctions && count($auctions)>0){
                foreach ($auctions as $key => $auction_id) {
                    \Log::channel('getAuctionsStatusLog')->info('call CheckAuctionStatusEvent');
                    event( new CheckAuctionStatusEvent($auction_id) );
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - CheckAuctionsStatus - " . $e->getMessage());
            \Log::channel('getAuctionsStatusLog')->error("ERROR - CheckAuctionsStatus - " . $e->getMessage());
        }

        \Log::channel('getAuctionsStatusLog')->info(date('Y-m-d H:i:s').' ======= End - CheckAuctionsStatus =======');
        $this->info(date('Y-m-d H:i:s').' ======= End - CheckAuctionsStatus =======');
    }
}
