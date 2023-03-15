<?php

namespace App\Listeners\Item;

use App\Events\Item\SellerAgreementConfirmationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class SellerAgreementConfirmationListener
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
     * @param  SellerAgreementConfirmationEvent  $event
     * @return void
     */
    public function handle(SellerAgreementConfirmationEvent $event)
    {
        \Log::channel('emailLog')->info('Start - SellerAgreementConfirmationEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\SellerAgreementConfirmation($customer));
        }

        \Log::channel('emailLog')->info('End - SellerAgreementConfirmationEvent');
    }
}
