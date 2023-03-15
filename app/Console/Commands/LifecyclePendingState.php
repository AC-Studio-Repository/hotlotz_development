<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Auction\Models\Auction;
use App\Helpers\NHelpers;
use App\Events\ItemLifcycleNextStageChangeEvent;
use DB;

class LifecyclePendingState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lifecycle:pending_state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change next stage from Pending State becuause between Auction 1 and Auction 2.';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - LifecyclePendingState =======');
        \Log::channel('lifecycleLog')->info('======= Start - LifecyclePendingState =======');

        try{

            $items = Item::where('items.status',Item::_PENDING_)
                    ->where('items.lifecycle_status',Item::_PENDING_FOR_AUCTION_)
                    ->join('item_lifecycles', function ($join) {
                        $join->on('item_lifecycles.id', '=', DB::raw('(SELECT id FROM item_lifecycles WHERE item_lifecycles.item_id = items.id and item_lifecycles.action is NULL and item_lifecycles.deleted_at is NULL LIMIT 1)'));
                    })
                    ->join('customers', 'customers.id', 'items.customer_id')
                    ->whereNull('customers.deleted_at')
                    ->select(
                        'items.name as item_name','items.status as item_status','items.lifecycle_status',
                        'item_lifecycles.*',
                        'customers.firstname','customers.lastname','customers.email as custEmail'
                    )->get();
            // dd($items);

            $this->info('Item count '.count($items));
            \Log::channel('lifecycleLog')->info('Item count '.count($items));

            if($items){
                foreach ($items as $key => $newitemlifecycle) {
                    // dd($newitemlifecycle);
                    $this->info('ItemLifecycle Type is '.$newitemlifecycle->type);
                    \Log::channel('lifecycleLog')->info('ItemLifecycle Type is '.$newitemlifecycle->type);

                    $eventstatus = false;

                    if($newitemlifecycle->type == 'auction'){
                        $auction2 = Auction::where('id','=',$newitemlifecycle->reference_id)->select('title','timed_start','timed_first_lot_ends', 'is_published', 'is_closed')->first();

                        if($auction2->is_published == 'Y' && $auction2->is_closed == 'N' && $auction2->timed_start <= date('Y-m-d H:i:s') ){

                            $this->info('This Auction 2 "'.$auction2->title.'" is start.');
                            \Log::channel('lifecycleLog')->info('This Auction 2 "'.$auction2->title.'" is start.');

                            $eventstatus = true;
                            $lifecycle_status = Item::_AUCTION_;

                            $email_data = [
                                'template' => 'item::itemlifecycle_email',
                                'to_email' => $newitemlifecycle->custEmail,
                                'subject' => 'Item Lifecycle Process',
                                'content' => 'Your "'.$newitemlifecycle->item_name.'" is located in "'.$auction2->title.'" Auction.',
                                'customer' => $newitemlifecycle->firstname.' '.$newitemlifecycle->lastname,
                            ];

                            event( new ItemLifcycleNextStageChangeEvent($newitemlifecycle, $lifecycle_status, $email_data) );
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - LifecyclePendingState - " . $e);
            \Log::channel('lifecycleLog')->error("ERROR - LifecyclePendingState - " . $e);
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - LifecyclePendingState =======');
        \Log::channel('lifecycleLog')->info('======= End - LifecyclePendingState =======');
    }
}
