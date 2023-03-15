<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Jobs\LotBotCreateJob;

class LotsBotCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:lots_bot_create {from_auction_id} {to_auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test GAP Lots bot create';

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
        \Log::info('======= Start - LotsBotCreate =======');

        try{
            $from_auction_id = $this->argument('from_auction_id');
            \Log::info('Get item_ids from auction_id : '.$from_auction_id);

            $to_auction_id = $this->argument('to_auction_id');
            \Log::info('Create lots into auction_id : '.$to_auction_id);

            $item_ids = AuctionItem::where('auction_id',$from_auction_id)
                        ->pluck('item_id');
            \Log::info('count of item_ids '.count($item_ids));

            if(count($item_ids) > 0){
                foreach ($item_ids as $key => $item_id) {
                    \Log::info('dispatch LotBotCreateJob');
                    LotBotCreateJob::dispatch($item_id, $from_auction_id, $to_auction_id);
                }
            }

        } catch (\Exception $e) {
            \Log::error("ERROR - LotsBotCreate - " . $e->getMessage());
        }

        $this->info('======= End - LotsBotCreate =======');
    }
}
