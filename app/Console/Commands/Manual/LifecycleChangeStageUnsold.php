<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Auction\Models\Auction;
use App\Helpers\NHelpers;
use App\Events\ItemLifcycleNextStageChangeEvent;
use DB;

class LifecycleChangeStageUnsold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lifecycle:change_stage_unsold {auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change next stage of Item Lifecycle for Unsold Items';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - LifecycleChangeStageUnsold =======');
        \Log::channel('lifecycleLog')->info('======= Start - LifecycleChangeStageUnsold =======');

        try{
            $auction_id = $this->argument('auction_id');
            \Log::channel('lifecycleLog')->info('auction_id : '.$auction_id);

            $item_ids = AuctionItem::where('auction_items.auction_id',$auction_id)
                    ->where('auction_items.status',Item::_UNSOLD_)
                    ->join('items','items.id','auction_items.item_id')
                    ->where('items.status',Item::_UNSOLD_)
                    ->pluck('auction_items.item_id')
                    ->all();

            $this->info('Item count '.count($item_ids));
            \Log::channel('lifecycleLog')->info('Item count '.count($item_ids));

            if(count($item_ids)>0){
                foreach ($item_ids as $key => $item_id) {
                    $current_item_lifecycle = ItemLifecycle::where('type','auction')->where('item_id',$item_id)->where('reference_id',$auction_id)->where('action',ItemLifecycle::_PROCESSING_)->first();

                    if(isset($current_item_lifecycle)){
                        \Log::channel('lifecycleLog')->info('current_item_lifecycle ID : '.$current_item_lifecycle->id);

                        \Log::channel('lifecycleLog')->info('call ItemLifcycleNextStageChangeEvent');
                        event( new ItemLifcycleNextStageChangeEvent($item_id, $current_item_lifecycle->id) );
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - LifecycleChangeStageUnsold - " . $e->getMessage());
            \Log::channel('lifecycleLog')->error("ERROR - LifecycleChangeStageUnsold - " . $e->getMessage());
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - LifecycleChangeStageUnsold =======');
        \Log::channel('lifecycleLog')->info('======= End - LifecycleChangeStageUnsold =======');
    }
}
