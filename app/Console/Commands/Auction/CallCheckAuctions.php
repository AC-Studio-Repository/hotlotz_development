<?php

namespace App\Console\Commands\Auction;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\CheckAuction;

class CallCheckAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:check_auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch CheckAuction Job before 1 day of auction closed date';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - CallCheckAuctions Command =======');
        \Log::channel('checkAuctionLog')->info('======= Start - CallCheckAuctions Command =======');


        $next_date = date('Y-m-d',strtotime("+1 day"));
        \Log::channel('checkAuctionLog')->info('next_date : '. $next_date);

        $auctions = Auction::whereNotNull('sr_auction_id')
                    ->where('is_closed','!=','Y')
                    ->whereDate('timed_first_lot_ends', $next_date)
                    ->select('id','title','timed_first_lot_ends','sr_auction_id','sr_reference')
                    ->get();
        \Log::channel('checkAuctionLog')->info('auctions count : '. count($auctions) );

        if(count($auctions) > 0){
            foreach ($auctions as $key => $auction) {
                \Log::channel('checkAuctionLog')->info('called CheckAuction Job '.$auction->id);
                $datetime = new \Carbon\Carbon($auction->timed_first_lot_ends);
                \Log::channel('checkAuctionLog')->info('auction end time '.$datetime);
                CheckAuction::dispatch($auction->id)->delay($datetime->addMinutes(10));
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - CallCheckAuctions Command =======');
        \Log::channel('checkAuctionLog')->info('======= End - CallCheckAuctions Command =======');
    }
}
