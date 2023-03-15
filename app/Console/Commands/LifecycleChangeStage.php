<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Auction\Models\Auction;
use App\Helpers\NHelpers;
use App\Events\ItemLifcycleNextStageChangeEvent;
use DB;

class LifecycleChangeStage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lifecycle:change_stage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change next stage of Item Lifecycle';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - LifecycleChangeStage =======');
        \Log::channel('lifecycleLog')->info('======= Start - LifecycleChangeStage =======');

        try{
            $items = Item::whereIn('items.status',[Item::_IN_MARKETPLACE_])
                    ->whereIn('items.lifecycle_status', ['marketplace','clearance'])
                    ->join('item_lifecycles', function ($join) {
                        $join->on('item_lifecycles.id', '=', DB::raw('(SELECT id FROM item_lifecycles WHERE item_lifecycles.item_id = items.id and item_lifecycles.action = "'.ItemLifecycle::_PROCESSING_.'" and item_lifecycles.deleted_at is NULL LIMIT 1)'));
                    })
                    ->select(
                        'items.id','item_lifecycles.id as itl_id','item_lifecycles.type','item_lifecycles.period','item_lifecycles.entered_date'
                    )
                    ->get();

            $this->info('Item count '.count($items));
            \Log::channel('lifecycleLog')->info('Item count '.count($items));

            if(count($items)>0){
                foreach ($items as $key => $itemlifecycle) {

                    $item_id = $itemlifecycle->id;
                    \Log::channel('lifecycleLog')->info('Item Id : '.$item_id.'');
                    
                    $this->info('ItemLifecycle Type is '.$itemlifecycle->type);
                    \Log::channel('lifecycleLog')->info('ItemLifecycle Type is '.$itemlifecycle->type);

                    if($itemlifecycle->type == 'marketplace' && $itemlifecycle->entered_date != null){
                        $entered_date = strtotime($itemlifecycle->entered_date);
                        $mp_finished_date = date('Y-m-d', strtotime('+'.intval($itemlifecycle->period).' day', $entered_date));

                        \Log::channel('lifecycleLog')->info('MP Finished Date is '.$mp_finished_date);

                        if($mp_finished_date <= date('Y-m-d H:i:s')){
                            \Log::channel('lifecycleLog')->info('This item needs to change next stage from Marketplace.');
                            event( new ItemLifcycleNextStageChangeEvent($item_id, $itemlifecycle->itl_id) );
                        }                
                    }

                    if($itemlifecycle->type == 'clearance' && $itemlifecycle->entered_date != null){
                        $entered_date = strtotime($itemlifecycle->entered_date);
                        $cl_finished_date = date('Y-m-d', strtotime('+'.intval($itemlifecycle->period).' day', $entered_date));

                        \Log::channel('lifecycleLog')->info('CL Finished Date is '.$cl_finished_date);

                        if($cl_finished_date <= date('Y-m-d H:i:s')){
                            \Log::channel('lifecycleLog')->info('This item needs to change next stage from Clearance.');
                            event( new ItemLifcycleNextStageChangeEvent($item_id, $itemlifecycle->itl_id) );
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - LifecycleChangeStage - " . $e->getMessage());
            \Log::channel('lifecycleLog')->error("ERROR - LifecycleChangeStage - " . $e->getMessage());
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - LifecycleChangeStage =======');
        \Log::channel('lifecycleLog')->info('======= End - LifecycleChangeStage =======');
    }
}