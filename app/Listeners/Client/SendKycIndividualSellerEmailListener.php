<?php

namespace App\Listeners\Client;

use App\Events\Client\SendKycIndividualSellerEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class SendKycIndividualSellerEmailListener
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
     * @param  SendKycIndividualSellerEmailEvent  $event
     * @return void
     */
    public function handle(SendKycIndividualSellerEmailEvent $event)
    {
        \Log::channel('emailLog')->info('Start - SendKycIndividualSellerEmailEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);
        if($customer != null){
            \Log::channel('emailLog')->info('KYC Individual Seller Email '.$customer->ref_no);
            Mail::to($customer->email)
                ->send(new \App\Mail\User\KycIndividualSellerEmail($customer));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your KYC Individual Seller mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your KYC Individual Seller mail');
            }
        }

        \Log::channel('emailLog')->info('End - SendKycIndividualSellerEmailEvent');
    }
}
