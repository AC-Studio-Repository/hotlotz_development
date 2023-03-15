<?php

namespace App\Listeners\Admin;

use App\Events\Admin\KycUpdateAlertEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Modules\Customer\Models\Customer;
use App\Modules\AdminEmail\Models\AdminEmail;

class KycUpdateAlertListener
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
     * @param  KycUpdateAlertEvent  $event
     * @return void
     */
    public function handle(KycUpdateAlertEvent $event)
    {
        \Log::channel('emailLog')->info('Start - KycUpdateAlertEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        // $old_data = $event->old_data;
        // \Log::channel('emailLog')->info('Client Old Data : '.print_r($old_data,true));
        // Customer::where('id', $customer_id)->update($old_data);

        $customer = Customer::find($customer_id);

        $emails = AdminEmail::where('type','kyc')->pluck('email')->all();
        \Log::channel('emailLog')->info('KYC Update Alert emails : '.print_r($emails,true) );

        if($customer != null && count($emails)>0){
            $first_email = array_shift($emails);

            Mail::to($first_email)->cc($emails)
                ->send(new \App\Mail\Admin\KycUpdateAlert($customer));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your KYC Update Alert mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your KYC Update Alert mail');
            }
        }

        \Log::channel('emailLog')->info('End - KycUpdateAlertEvent');
    }
}
