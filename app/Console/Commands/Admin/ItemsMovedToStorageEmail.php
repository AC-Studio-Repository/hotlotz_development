<?php

namespace App\Console\Commands\Admin;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\AdminEmail\Models\AdminEmail;

class ItemsMovedToStorageEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:items_moved_to_storage_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Items moved to Storage Email to Admin';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - ItemsMovedToStorageEmail Command =======');
        \Log::info('======= Start - ItemsMovedToStorageEmail Command =======');
        \Log::channel('emailLog')->info('======= Start - ItemsMovedToStorageEmail Command =======');

        $today = new Carbon();

        $from = Carbon::parse(date('Y-m-d'))->yesterday()->hour(9);
        \Log::channel('emailLog')->info('from date : '.$from );

        $to = Carbon::parse(date('Y-m-d'))->hour(9);
        \Log::channel('emailLog')->info('to date : '.$to );


        $items = Item::whereIn('items.status',[Item::_UNSOLD_])
                ->where('items.tag', '!=', 'dispatched')
                ->where('storage_date', '>=', $from)
                ->where('storage_date', '<', $to)
                ->get();
        \Log::channel('emailLog')->info('ItemsMovedToStorageEmail items count : '.count($items) );


        $storage_items = [];
        foreach ($items as $key => $value) {
            $item_lifecycle = ItemLifecycle::where('item_id',$value->id)
                            ->where('type','!=','storage')
                            ->orderBy('id','desc')
                            ->first();

            if($item_lifecycle != null && $item_lifecycle->action == ItemLifecycle::_FINISHED_ && in_array($item_lifecycle->type, ['marketplace', 'clearance']) ){
                \Log::channel('emailLog')->info('ItemsMovedToStorageEmail item_lifecycle : '.print_r($item_lifecycle->id,true) );

                $storage_items[] = [
                    'name' => $value->name,
                    'item_number' => $value->item_number,
                    'item_link' => config('app.admin_domain').route('item.items.show_item', [$value->id,'cataloguing'], false),
                    'item_link2' => config('app.admin_domain').route('item.items.show_item', [$value->id,'overview'], false),
                    'category_name' => (isset($value->category) && isset($value->category_id))?$value->category->name:null,
                    'seller_link' => config('app.admin_domain').route('customer.customers.show', $value->customer, false),
                    'seller' => $value->customer->fullname,
                    'storage_date' => Carbon::parse($value->storage_date)->toDayDateTimeString(),
                    'created_at' => ($value->created_at)->toDayDateTimeString(),
                ];
            }            
        }
        \Log::channel('emailLog')->info('ItemsMovedToStorageEmail storage_items : '.print_r($storage_items,true) );
        

        $emails = AdminEmail::where('type','items_moved_to_storage')->pluck('email')->all();
        \Log::channel('emailLog')->info('ItemsMovedToStorageEmail emails : '.print_r($emails,true) );


        if(count($emails)>0 != null && count($storage_items)>0){
            $first_email = array_shift($emails);

            Mail::to($first_email)->cc($emails)->send(new \App\Mail\Admin\ItemsMovedToStorageEmail($storage_items));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your ItemsMovedToStorageEmail mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your ItemsMovedToStorageEmail mail');
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - ItemsMovedToStorageEmail Command =======');
        \Log::info('======= End - ItemsMovedToStorageEmail Command =======');
        \Log::channel('emailLog')->info('======= End - ItemsMovedToStorageEmail Command =======');
    }
}
