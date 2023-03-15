<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Jobs\LifecycleStart;

class AuctionItemsLifecycleStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:lifecycle_start {auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to trigger the LifecycleStart job based on auction id';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - AuctionItemsLifecycleStart =======');
        \Log::channel('lifecycleLog')->info('======= Start - AuctionItemsLifecycleStart =======');

        try{
            $auction_id = $this->argument('auction_id');
            \Log::channel('lifecycleLog')->info('auction_id : '.$auction_id);

            $today = date('Y-m-d H:i:s');
            $item_history_status = '';
            $item_data = [];
            $auction = Auction::find($auction_id);

            if($auction != null && $auction->sr_auction_id != null){

                $item_ids = AuctionItem::where('auction_id',$auction_id)
                            ->whereNull('items.deleted_at')
                            ->join('items','items.id','auction_items.item_id')
                            ->whereNull('items.deleted_at')
                            ->pluck('item_id');

                \Log::channel('lifecycleLog')->info('count of auctions '.count($item_ids));

                $count = 0;
                if($item_ids && count($item_ids)>0){
                    foreach ($item_ids as $key => $item_id) {
                        $item = Item::find($item_id);

                        if($item != null && $item->is_cataloguing_approved === 'Y' && $item->permission_to_sell === 'Y' && $item->status === Item::_PENDING_){

                            $count ++;
                            
                            \Log::channel('lifecycleLog')->info('AuctionItemsLifecycleStart - dispatch LifecycleStart Job '.$item_id);
                            LifecycleStart::dispatch($item_id);
                        }
                    }
                    \Log::channel('lifecycleLog')->info('AuctionItemsLifecycleStart - total items '.$count);
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - AuctionItemsLifecycleStart - " . $e->getMessage());
            \Log::channel('lifecycleLog')->error("ERROR - AuctionItemsLifecycleStart - " . $e->getMessage());
        }

        \Log::channel('lifecycleLog')->info(date('Y-m-d H:i:s').' ======= End - AuctionItemsLifecycleStart =======');
        $this->info(date('Y-m-d H:i:s').' ======= End - AuctionItemsLifecycleStart =======');
    }
}
