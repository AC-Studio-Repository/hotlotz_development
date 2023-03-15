<?php

namespace App\Listeners\Client;

use App\Events\Client\BankAccountUpdateAlertEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\AdminEmail\Models\AdminEmail;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class BankAccountUpdateAlertListener
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
     * @param  BankAccountUpdateAlertEvent  $event
     * @return void
     */
    public function handle(BankAccountUpdateAlertEvent $event)
    {
        \Log::channel('emailLog')->info('Start - BankAccountUpdateAlertEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $old_data = $event->old_data;
        \Log::channel('emailLog')->info('Client Old Data : '.print_r($old_data,true));
        Customer::where('id', $customer_id)->update($old_data);

        $info_data = $event->info_data;
        \Log::channel('emailLog')->info('Client Info Data : '.print_r($info_data,true));

        $emails = AdminEmail::where('type','bau')->pluck('email')->all();
        \Log::channel('emailLog')->info('BankAccountUpdateAlert emails : '.print_r($emails,true) );

        $customer = Customer::find($customer_id);
        if($customer != null && count($emails)>0){
            $first_email = array_shift($emails);
            \Log::channel('emailLog')->info('first_email  : '.print_r($first_email,true) );
            \Log::channel('emailLog')->info('emails  : '.print_r($emails,true) );
            
            Mail::to($first_email)->cc($emails)
                ->send(new \App\Mail\Admin\BankAccountUpdateAlert($customer, $info_data));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your BankAccountUpdateAlert mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your BankAccountUpdateAlert mail');
            }
        }

        \Log::channel('emailLog')->info('End - BankAccountUpdateAlertEvent');
    }
}
