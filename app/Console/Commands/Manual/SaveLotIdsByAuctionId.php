<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;

class SaveLotIdsByAuctionId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:save_lot_ids {auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save Lot IDs by Auction ID';

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
        \Log::info('======= Start - SaveLotIdsByAuctionId =======');

        try{
            $auction_id = $this->argument('auction_id');
            \Log::info('auction_id : '.$auction_id);

            $auction = Auction::find($auction_id);

            if(isset($auction)){

                $lots = Item::getLotsByAuctionId($auction->sr_auction_id);

                if(count($lots) > 0){
                    foreach ($lots as $key => $lot) {
                        $data['lot_id'] = $lot['lot_id'];
                        AuctionItem::where('auction_id',$auction_id)->where('lot_number',$lot['lot_number'])->update($data);
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::error("ERROR - SaveLotIdsByAuctionId - " . $e->getMessage());
        }

        \Log::info('======= End - SaveLotIdsByAuctionId =======');
    }
}
