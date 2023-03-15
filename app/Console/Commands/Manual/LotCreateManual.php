<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Jobs\LotCreateJob;

class LotCreateManual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:lot
            {--item_id= : Access items table id.}
            {--item_ids= : Access multi item ids separate by comma.}
            {--auction_id= : Access auctions table id.}
            {--auction_ids= : Access multi auction ids separate by comma.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To trigger the lot create job';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - LotCreateManual =======');
        \Log::channel('gapLog')->info('======= Start - LotCreateManual =======');

        try{
            // \Log::channel('gapLog')->info('options : '.print_r($this->options(), true));
            $options = $this->getOptions();

            if(isset($options['item_id']) && isset($options['auction_id'])){
                $this->createLotbyItemId($options['item_id'], $options['auction_id']);
            }

            if(isset($options['item_ids']) && count($options['item_ids'])>0 && isset($options['auction_id'])){
                foreach ($options['item_ids'] as $key => $item_id) {
                    $this->createLotbyItemId($item_id, $options['auction_id']);
                }
            }

            if(isset($options['auction_id'])){
                $this->createLotsbyAuctionId($options['auction_id']);
            }

            if(isset($options['auction_ids']) && count($options['auction_ids'])>0){
                foreach ($options['auction_ids'] as $key => $auction_id) {
                    $this->createLotsbyAuctionId($auction_id);
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - LotCreateManual - " . $e->getMessage());
            \Log::channel('gapLog')->error("ERROR - LotCreateManual - " . $e->getMessage());
        }

        \Log::channel('gapLog')->info(date('Y-m-d H:i:s').' ======= End - LotCreateManual =======');
        $this->info('======= End - LotCreateManual =======');
    }

    protected function getOptions()
    {
        $item_id = $this->option('item_id');
        \Log::channel('gapLog')->info('item_id : '.$item_id);

        $auction_id = $this->option('auction_id');
        \Log::channel('gapLog')->info('auction_id : '.$auction_id);

        $item_ids_option = $this->option('item_ids');
        $item_ids = [];
        if(isset($item_ids_option)){
            $item_ids = explode(",", $item_ids_option);
        }
        \Log::channel('gapLog')->info('item_ids : '.print_r($item_ids,true));

        $auction_ids_option = $this->option('auction_ids');
        $auction_ids = [];
        if(isset($auction_ids_option)){
            $auction_ids = explode(",", $auction_ids_option);
        }
        \Log::channel('gapLog')->info('auction_ids : '.print_r($auction_ids,true));

        return [
            'item_id' => $item_id,
            'item_ids' => $item_ids,
            'auction_id' => $auction_id,
            'auction_ids' => $auction_ids,
        ];
    }

    protected function createLotbyItemId($item_id, $auction_id)
    {
        $auction = Auction::find($auction_id);

        if($auction != null && $auction->is_closed != 'Y' && $auction->sr_auction_id != null){
            \Log::channel('gapLog')->info('dispatch LotCreateJob for item_id :'.$item_id);
            LotCreateJob::dispatch($item_id, $auction_id, $auction->sr_auction_id);
        }
    }

    protected function createLotsbyAuctionId($auction_id)
    {
        $auction = Auction::find($auction_id);

        if($auction != null && $auction->is_closed != 'Y' && $auction->sr_auction_id != null){
            $auction_item_ids = AuctionItem::where('auction_id',$auction_id)
                                ->whereNull('lot_id')
                                ->pluck('item_id');

            \Log::channel('gapLog')->info('count of auction item ids '.count($auction_item_ids));

            if($auction_item_ids && count($auction_item_ids)>0){
                foreach ($auction_item_ids as $key => $item_id) {
                    \Log::channel('gapLog')->info('dispatch LotCreateJob for item_id :'.$item_id);
                    LotCreateJob::dispatch($item_id, $auction_id, $auction->sr_auction_id);
                }
            }
        }
    }
}
