<?php

namespace App\Listeners\Client;

use App\Events\Client\SendKycBuyerEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class SendKycBuyerEmailListener
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
     * @param  SendKycBuyerEmailEvent  $event
     * @return void
     */
    public function handle(SendKycBuyerEmailEvent $event)
    {
        \Log::channel('emailLog')->info('Start - SendKycBuyerEmailEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);
        if($customer != null){
            \Log::channel('emailLog')->info('KYC Buyer Email '.$customer->ref_no);
            Mail::to($customer->email)
                ->send(new \App\Mail\User\KycBuyerEmail($customer));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your KYC Buyer mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your KYC Buyer mail');
            }
        }

        \Log::channel('emailLog')->info('End - SendKycBuyerEmailEvent');
    }
}
