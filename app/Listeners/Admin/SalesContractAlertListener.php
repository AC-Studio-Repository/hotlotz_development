<?php

namespace App\Listeners\Admin;

use Carbon\Carbon;
use App\Events\Admin\SalesContractAlertEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Modules\Item\Models\Item;
use App\Modules\Customer\Models\Customer;
use App\Modules\AdminEmail\Models\AdminEmail;

class SalesContractAlertListener
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
     * @param  SalesContractAlertEvent  $event
     * @return void
     */
    public function handle(SalesContractAlertEvent $event)
    {
        \Log::channel('emailLog')->info('Start - SalesContractAlertEvent');

        $item_ids = $event->item_ids;
        \Log::channel('emailLog')->info('item_ids : '.print_r($item_ids,true));

        $items = [];
        foreach ($item_ids as $key => $item_id) {
            $item = Item::find($item_id);
            if($item != null){
                $items[] = [
                    'name' => $item->name,
                    'item_number' => $item->item_number,
                    'item_link' => config('app.admin_domain').route('item.items.show_item', [$item->id,'cataloguing'], false),
                    'item_link2' => config('app.admin_domain').route('item.items.show_item', [$item->id,'overview'], false),
                    'category_name' => (isset($item->category) && isset($item->category_id))?$item->category->name:null,
                    'seller_link' => config('app.admin_domain').route('customer.customers.show', $item->customer, false),
                    'seller' => $item->customer->fullname,
                    'seller_agreement_signed_date' => Carbon::parse($item->seller_agreement_signed_date)->toDayDateTimeString(),
                    'created_at' => ($item->created_at)->toDayDateTimeString(),
                ];
            }
        }
        \Log::channel('emailLog')->info('SalesContractAlert items : '.print_r($items,true) );
        

        $emails = AdminEmail::where('type','sales_contract')->pluck('email')->all();
        \Log::channel('emailLog')->info('SalesContractAlert emails : '.print_r($emails,true) );


        if(count($emails)>0 != null && count($items)>0){
            $first_email = array_shift($emails);

            Mail::to($first_email)->cc($emails)->send(new \App\Mail\Admin\SalesContractAlert($items));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your SalesContractAlert mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your SalesContractAlert mail');
            }
        }

        \Log::channel('emailLog')->info('End - SalesContractAlertEvent');
    }
}
