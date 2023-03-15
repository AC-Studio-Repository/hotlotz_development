<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Auction\Models\Auction;

class ItemStatusManualRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:status_rollback {status : dispatch or storage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Item Lifecycle Status Manual Rollback for "Dispatched" and "Storage"';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - ItemStatusManualRollback =======');
        \Log::info('======= Start - ItemStatusManualRollback =======');

        try{
            $status = $this->argument('status');
            \Log::info('status : '.$status);

            if($status == 'dispatch') {
                \Log::info('dispatch if');
                $items = Item::where('status', Item::_DISPATCHED_)->get();
                \Log::info('dispatch items count : '.count($items));
                $data = [
                    'tag' => 'dispatched',
                ];
                foreach ($items as $key => $item) {
                    if($item->lifecycle_status == Item::_STORAGE_) {
                        \Log::info('dispatched-storage item_id : '.$item->id);
                        $data['status'] = Item::_UNSOLD_;
                        Item::where('id',$item->id)->update($data);
                    }

                    if(in_array($item->lifecycle_status, [Item::_AUCTION_, Item::_MARKETPLACE_, Item::_CLEARANCE_, Item::_PRIVATE_SALE_]) || $item->lifecycle_status == null) {
                        \Log::info('dispatched-not-storage item_id : '.$item->id);
                        if($item->settled_date != null) {
                            $data['status'] = Item::_SETTLED_;
                            Item::where('id',$item->id)->update($data);
                        }
                        if($item->paid_date != null && $item->settled_date == null) {
                            $data['status'] = Item::_PAID_;
                            Item::where('id',$item->id)->update($data);
                        }
                        if($item->sold_date != null && $item->paid_date == null && $item->settled_date == null) {
                            $data['status'] = Item::_SOLD_;
                            Item::where('id',$item->id)->update($data);
                        }
                        if($item->lifecycle_status == null && $item->sold_date == null && $item->paid_date == null && $item->settled_date == null) {
                            $data['status'] = Item::_UNSOLD_;
                            Item::where('id',$item->id)->update($data);
                        }
                    }
                }
            }

            if($status == 'storage') {
                \Log::info('storage if');
                $items = Item::where('status', Item::_STORAGE_)->get();
                \Log::info('storage items count : '.count($items));
                $data = [
                    'status' => Item::_UNSOLD_,
                    'tag' => 'in_storage',
                ];
                foreach ($items as $key => $item) {
                    if($item->lifecycle_status == Item::_STORAGE_) {
                        \Log::info('storage item_id : '.$item->id);
                        Item::where('id',$item->id)->update($data);
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - ItemStatusManualRollback - " . $e->getMessage());
            \Log::error("ERROR - ItemStatusManualRollback - " . $e->getMessage());
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - ItemStatusManualRollback =======');
        \Log::info('======= End - ItemStatusManualRollback =======');
    }
}
