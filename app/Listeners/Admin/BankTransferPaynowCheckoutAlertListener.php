<?php

namespace App\Listeners\Admin;

use App\Events\Admin\BankTransferPaynowCheckoutAlertEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\AdminEmail\Models\AdminEmail;
use App\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;

class BankTransferPaynowCheckoutAlertListener
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
     * @param  BankTransferPaynowCheckoutAlertEvent  $event
     * @return void
     */
    public function handle(BankTransferPaynowCheckoutAlertEvent $event)
    {
        \Log::channel('emailLog')->info('Start - BankTransferPaynowCheckoutAlertEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $invoice_number = $event->invoice_number;
        \Log::channel('emailLog')->info('invoice_number : '.$invoice_number);

        $invoice_url = $event->invoice_url;
        \Log::channel('emailLog')->info('invoice_url : '.$invoice_url);

        $customer = Customer::find($customer_id);

        $emails = AdminEmail::where('type','bank_paynow_checkout')->pluck('email')->all();
        \Log::channel('emailLog')->info('BankTransferPaynowCheckoutAlert emails : '.print_r($emails,true) );

        if($customer != null && count($emails)>0 && $invoice_number != null){
            $first_email = array_shift($emails);

            Mail::to($first_email)->cc($emails)
                ->send(new \App\Mail\Admin\BankTransferPaynowCheckoutAlert($customer, $invoice_number, $invoice_url));

            if (Mail::failures()) {
                \Log::channel('emailLog')->info('Sorry! Please try again latter for your Bank Transfer/Paynow Checkout Alert mail');
            } else {
                \Log::channel('emailLog')->info('Great! Successfully send in your Bank Transfer/Paynow Checkout Alert mail');
            }
        }

        \Log::channel('emailLog')->info('End - BankTransferPaynowCheckoutAlertEvent');
    }
}
