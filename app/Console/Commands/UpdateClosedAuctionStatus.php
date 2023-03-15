<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use App\Events\UpdateClosedAuctionStatusEvent;

class UpdateClosedAuctionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:update_closed_auction_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update closed Auction\'s status';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - UpdateClosedAuctionStatus =======');
        \Log::info(date('Y-m-d H:i:s').' ======= Start - UpdateClosedAuctionStatus =======');

        try{
            $auctions = Auction::where('is_closed','Y')
                        ->whereNotNull('sr_auction_id')
                        ->where('status','!=','Invoiced')
                        ->where('is_exist_gap_toolbox','!=','N')
                        ->pluck('id');

            \Log::info('count of auctions '.count($auctions));

            if($auctions && count($auctions)>0){
                foreach ($auctions as $key => $auction_id) {
                    \Log::info('call UpdateClosedAuctionStatusEvent '.$auction_id);
                    event( new UpdateClosedAuctionStatusEvent($auction_id) );
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - UpdateClosedAuctionStatus - " . $e->getMessage());
            \Log::error("ERROR - UpdateClosedAuctionStatus - " . $e->getMessage());
        }

        \Log::info(date('Y-m-d H:i:s').' ======= End - UpdateClosedAuctionStatus =======');
        $this->info(date('Y-m-d H:i:s').' ======= End - UpdateClosedAuctionStatus =======');
    }
}
