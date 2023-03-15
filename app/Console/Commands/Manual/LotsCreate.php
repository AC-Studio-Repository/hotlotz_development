<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Jobs\LotCreateJob;

class LotsCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:lots_create {auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to trigger the lot create job based on auction id';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - LotsCreate =======');
        \Log::channel('gapLog')->info('======= Start - LotsCreate =======');

        try{
            $auction_id = $this->argument('auction_id');
            \Log::channel('gapLog')->info('auction_id : '.$auction_id);

            $auction = Auction::find($auction_id);

            if(isset($auction) && $auction->sr_auction_id != null){

                $item_ids = AuctionItem::where('auction_id',$auction_id)
                            ->whereNull('lot_id')
                            ->pluck('item_id');

                \Log::channel('gapLog')->info('count of auctions '.count($item_ids));

                if($item_ids && count($item_ids)>0){
                    foreach ($item_ids as $key => $item_id) {
                        \Log::channel('gapLog')->info('dispatch LotCreateJob');
                        LotCreateJob::dispatch($item_id, $auction_id, $auction->sr_auction_id);
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - LotsCreate - " . $e->getMessage());
            \Log::channel('gapLog')->error("ERROR - LotsCreate - " . $e->getMessage());
        }

        \Log::channel('gapLog')->info(date('Y-m-d H:i:s').' ======= End - LotsCreate =======');
        $this->info('======= End - LotsCreate =======');
    }
}
