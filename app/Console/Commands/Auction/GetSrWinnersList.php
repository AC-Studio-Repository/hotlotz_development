<?php

namespace App\Console\Commands\Auction;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use Illuminate\Support\Facades\Artisan;

class GetSrWinnersList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:get_sr_winners_list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - GetSrWinnersList Command =======');
        \Log::info('======= Start - GetSrWinnersList Command =======');


        $sr_auction_id = "a49c0883-3506-41dc-a70c-ac0f006ce41f";
        $winners_list = Auction::getWinnersByAuctionId($sr_auction_id);

        $count = count($winners_list);

        if($count > 1){
            $count = 1;
        }

        for ($i=0; $i < $count; $i++) {
            \Log::info('bidder_id : '.$winners_list[$i]['bidder_id']);
            \Log::info('called gap:create_sr_customer_account');
            Artisan::call('gap:create_sr_customer_account', ['bidder_id'=>$winners_list[$i]['bidder_id']]);
        }

        // foreach ($winners_list as $key => $winner) {
        //     \Log::info('bidder_id : '.$winner['bidder_id']);
        //     \Log::info('called gap:create_sr_customer_account');
        //     Artisan::call('gap:create_sr_customer_account', ['bidder_id'=>$winner['bidder_id']]);
        // }


        $this->info(date('Y-m-d H:i:s').' ======= End - GetSrWinnersList Command =======');
        \Log::info('======= End - GetSrWinnersList Command =======');
    }
}
