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

class WeeklySellWithUs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly:sell_with_us';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Weekly Sell With Us Report';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - WeeklySellWithUs Command =======');
        \Log::info('======= Start - WeeklySellWithUs Command =======');
        \Log::channel('emailLog')->info('======= Start - WeeklySellWithUs Command =======');

        $today = new Carbon();

        $from = Carbon::parse(date('Y-m-d'))->previous('Tuesday');
        $from = $from->hour(9)->minute(1);
        \Log::channel('emailLog')->info('from date : '.$from );

        $to = Carbon::parse(date('Y-m-d'));
        $to = $to->hour(9);
        \Log::channel('emailLog')->info('to date : '.$to );


        $items = Item::where('status', Item::_SWU_)->whereBetween('created_at', [$from, $to])->orderBy('item_number')->get();
        \Log::channel('emailLog')->info('WeeklySellWithUs items count : '.count($items) );

        $swu_items = [];
        foreach ($items as $key => $value) {
            $swu_items[] = [
                'name' => $value->name,
                'item_number' => $value->item_number,
                'item_link' => config('app.admin_domain').route('item.items.show_item', [$value->id,'cataloguing'], false),
                'item_link2' => config('app.admin_domain').route('item.items.show_item', [$value->id,'overview'], false),
                'category_name' => (isset($value->category) && isset($value->category_id))?$value->category->name:null,
                'seller_link' => config('app.admin_domain').route('customer.customers.show', $value->customer, false),
                'seller' => $value->customer->fullname,
                'created_at' => ($value->created_at)->toDayDateTimeString(),
            ];
        }
        // \Log::channel('emailLog')->info('WeeklySellWithUs swu_items : '.print_r($swu_items,true) );

        $emails = AdminEmail::where('type','swu')->pluck('email')->all();
        \Log::channel('emailLog')->info('WeeklySellWithUs emails : '.print_r($emails,true) );


        if(count($emails)>0 != null && count($swu_items)>0){
            $first_email = array_shift($emails);

            Mail::to($first_email)->cc($emails)->send(new \App\Mail\Admin\WeeklySellWithUs($swu_items));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your WeeklySellWithUs mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your WeeklySellWithUs mail');
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - WeeklySellWithUs Command =======');
        \Log::info('======= End - WeeklySellWithUs Command =======');
        \Log::channel('emailLog')->info('======= End - WeeklySellWithUs Command =======');
    }
}
