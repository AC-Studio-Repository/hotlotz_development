<?php

namespace App\Listeners\Item;

use App\Events\Item\ConsignmentUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;
use App\Modules\Item\Models\ItemHistory;
use App\Helpers\NHelpers;

class ConsignmentUpdateListener
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
     * @param  ConsignmentUpdateEvent  $event
     * @return void
     */
    public function handle(ConsignmentUpdateEvent $event)
    {
        \Log::channel('emailLog')->info('Start - ConsignmentUpdateEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $data = $event->data;

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\ConsignmentUpdate($data));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your ConsignmentUpdate mail');

                if( isset($data['auction_results']) ){
                    foreach ($data['auction_results'] as $key => $itemhistory) {
                        \Log::channel('emailLog')->info('For email_flag update, ItemHistory Id : '.$itemhistory['id']);
                        ItemHistory::where('id',$itemhistory['id'])->update( ['email_flag'=>'Completed'] + NHelpers::updated_at_by() );
                    }
                }

                if( isset($data['mp_results']) ){
                    foreach ($data['mp_results'] as $key => $itemhistory) {
                        \Log::channel('emailLog')->info('For email_flag update, ItemHistory Id : '.$itemhistory['id']);
                        ItemHistory::where('id',$itemhistory['id'])->update( ['email_flag'=>'Completed'] + NHelpers::updated_at_by() );
                    }
                }

                if( isset($data['notifications']) ){
                    foreach ($data['notifications'] as $key => $itemhistory) {
                        \Log::channel('emailLog')->info('For email_flag update, ItemHistory Id : '.$itemhistory['id']);
                        ItemHistory::where('id',$itemhistory['id'])->update( ['email_flag'=>'Completed'] + NHelpers::updated_at_by() );
                    }
                }
            }
        }

        \Log::channel('emailLog')->info('End - ConsignmentUpdateEvent');
    }
}
