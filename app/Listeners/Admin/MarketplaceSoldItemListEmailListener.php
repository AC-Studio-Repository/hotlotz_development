<?php

namespace App\Listeners\Admin;

use App\Events\Admin\MarketplaceSoldItemListEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Modules\Auction\Models\Auction;
use Illuminate\Support\Facades\Artisan;
use App\Modules\AdminEmail\Models\AdminEmail;
use Carbon\Carbon;

class MarketplaceSoldItemListEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MarketplaceSoldItemListEmailEvent  $event
     * @return void
     */
    public function handle(MarketplaceSoldItemListEmailEvent $event)
    {
        \Log::channel('emailLog')->info('Start - MarketplaceSoldItemListEmailEvent');

        try {
            $payload = $event->payload;
            \Log::channel('emailLog')->info('payload : '.print_r($payload['items'],true));

            $mp_sold_items = [];
            foreach ($payload['items'] as $key => $value) {
                $item = Item::find($value['id']);
                if($item != null){
                    $mp_sold_items[] = [
                        'name' => $item->name,
                        'item_number' => $item->item_number,
                        'item_link' => config('app.admin_domain').route('item.items.show_item', [$item->id,'item_purchase'], false),
                        'item_link2' => config('app.admin_domain').route('item.items.show_item', [$item->id,'overview'], false),
                        'category_name' => (isset($item->category) && isset($item->category_id))?$item->category->name:null,
                        'seller_link' => config('app.admin_domain').route('customer.customers.show', $item->customer, false),
                        'seller' => $item->customer->fullname,
                        'sold_date' => Carbon::parse($item->sold_date)->toDayDateTimeString(),
                        'created_at' => ($item->created_at)->toDayDateTimeString(),
                    ];
                }
            }
            \Log::channel('emailLog')->info('MarketplaceSoldItemListEmail mp_sold_items : '.print_r($mp_sold_items,true) );

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

        } catch (\Exception $e) {
            \Log::channel('emailLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('emailLog')->info('End - MarketplaceSoldItemListEmailEvent');
    }
}
