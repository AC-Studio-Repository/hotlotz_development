<?php

namespace App\Listeners\Client;

use App\Events\Client\ProfileUpdateAlertEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\AdminEmail\Models\AdminEmail;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class ProfileUpdateAlertListener
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
     * @param  ProfileUpdateAlertEvent  $event
     * @return void
     */
    public function handle(ProfileUpdateAlertEvent $event)
    {
        \Log::channel('emailLog')->info('Start - ProfileUpdateAlertEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer_old_data = $event->old_data;
        \Log::channel('emailLog')->info('Client Old Data : '.print_r($customer_old_data,true));
        Customer::where('id', $customer_id)->update($customer_old_data);

        $info_data = $event->info_data;
        \Log::channel('emailLog')->info('Client Info Data : '.print_r($info_data,true));

        $emails = AdminEmail::where('type','profile')->pluck('email')->all();
        \Log::channel('emailLog')->info('ProfileUpdateAlert emails : '.print_r($emails,true) );


        $customer = Customer::find($customer_id);
        if($customer != null && count($emails)>0){
            $first_email = array_shift($emails);

            Mail::to($first_email)->cc($emails)
                ->send(new \App\Mail\Admin\ProfileUpdateAlert($customer, $info_data));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your ProfileUpdateAlert mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your ProfileUpdateAlert mail');
            }
        }

        \Log::channel('emailLog')->info('End - ProfileUpdateAlertEvent');
    }
}
