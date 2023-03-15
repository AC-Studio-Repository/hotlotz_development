<?php

namespace App\Listeners\Client;

use App\Events\Client\SendKycCompanySellerEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class SendKycCompanySellerEmailListener
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
     * @param  SendKycCompanySellerEmailEvent  $event
     * @return void
     */
    public function handle(SendKycCompanySellerEmailEvent $event)
    {
        \Log::channel('emailLog')->info('Start - SendKycCompanySellerEmailEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);
        if($customer != null){
            \Log::channel('emailLog')->info('KYC Company Seller Email '.$customer->ref_no);
            Mail::to($customer->email)
                ->send(new \App\Mail\User\KycCompanySellerEmail($customer));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your KYC Company Seller mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your KYC Company Seller mail');
            }
        }

        \Log::channel('emailLog')->info('End - SendKycCompanySellerEmailEvent');
    }
}
