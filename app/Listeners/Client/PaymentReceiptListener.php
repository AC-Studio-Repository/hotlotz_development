<?php

namespace App\Listeners\Client;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use App\Events\Client\PaymentReceiptEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentReceiptListener
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
     * @param  PaymentReceiptEvent  $event
     * @return void
     */
    public function handle(PaymentReceiptEvent $event)
    {
        \Log::channel('emailLog')->info('Start - PaymentReceiptEvent');

        $customer_id = $event->customer_id;
        $itemNames = $event->itemNames;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\User\PaymentReceipt($itemNames));
        }

        \Log::channel('emailLog')->info('End - PaymentReceiptEvent');
    }
}
