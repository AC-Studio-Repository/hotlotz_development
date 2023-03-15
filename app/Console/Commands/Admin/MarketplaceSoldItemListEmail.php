<?php

namespace App\Console\Commands\Admin;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Modules\Auction\Models\Auction;
use Illuminate\Support\Facades\Artisan;
use App\Modules\AdminEmail\Models\AdminEmail;

class MarketplaceSoldItemListEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:mp_sold_items_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marketplace Sold Item List Email to Admin';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - MarketplaceSoldItemListEmail Command =======');
        \Log::info('======= Start - MarketplaceSoldItemListEmail Command =======');

        $today = new Carbon();

        $from = Carbon::parse(date('Y-m-d'))->yesterday()->hour(9);
        \Log::channel('emailLog')->info('from date : '.$from );

        $to = Carbon::parse(date('Y-m-d'))->hour(9);
        \Log::channel('emailLog')->info('to date : '.$to );


        $items = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_, Item::_CLEARANCE_])
                ->whereIn('status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                ->where('items.tag', '!=', 'dispatched')
                ->whereBetween('sold_date', [$from, $to])
                ->get();
        \Log::channel('emailLog')->info('MarketplaceSoldItemListEmail items count : '.count($items) );

        $mp_sold_items = [];
        foreach ($items as $key => $value) {
            $mp_sold_items[] = [
                'name' => $value->name,
                'item_number' => $value->item_number,
                'item_link' => config('app.admin_domain').route('item.items.show_item', [$value->id,'item_purchase'], false),
                'item_link2' => config('app.admin_domain').route('item.items.show_item', [$value->id,'overview'], false),
                'category_name' => (isset($value->category) && isset($value->category_id))?$value->category->name:null,
                'seller_link' => config('app.admin_domain').route('customer.customers.show', $value->customer, false),
                'seller' => $value->customer->fullname,
                'sold_date' => Carbon::parse($value->sold_date)->toDayDateTimeString(),
                'created_at' => ($value->created_at)->toDayDateTimeString(),
            ];
        }
        // \Log::channel('emailLog')->info('MarketplaceSoldItemListEmail mp_sold_items : '.print_r($mp_sold_items,true) );

        $emails = AdminEmail::where('type','mp_sold_items')->pluck('email')->all();
        \Log::channel('emailLog')->info('MarketplaceSoldItemListEmail emails : '.print_r($emails,true) );


        if(count($emails)>0 != null && count($mp_sold_items)>0){
            $first_email = array_shift($emails);

            Mail::to($first_email)->cc($emails)->send(new \App\Mail\Admin\MarketplaceSoldItemListEmail($mp_sold_items));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your MarketplaceSoldItemListEmail mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your MarketplaceSoldItemListEmail mail');
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - MarketplaceSoldItemListEmail Command =======');
        \Log::info('======= End - MarketplaceSoldItemListEmail Command =======');
    }
}
