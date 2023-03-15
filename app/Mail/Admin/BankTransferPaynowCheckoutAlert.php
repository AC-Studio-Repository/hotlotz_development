<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BankTransferPaynowCheckoutAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $customer;
    public $invoice_number;
    public $invoice_url;
    public function __construct($customer, $invoice_number, $invoice_url)
    {
        $this->customer = $customer;
        $this->invoice_number = $invoice_number;
        $this->invoice_url = $invoice_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'subject' => "Bank Transfer/PayNow Checkout Alert",
            'client' => $this->customer->ref_no.'_'.$this->customer->fullname,
            'invoice_number' => $this->invoice_number,
            'invoice_url' => $this->invoice_url,
            'link' =>  config('app.admin_domain').route('customer.customers.show', $this->customer, false),
        ];
        \Log::channel('emailLog')->info('BankTransferPaynowCheckoutAlert data : '.print_r($data,true) );

        return $this->subject('Bank Transfer/PayNow Checkout Alert')->view('emails.admin.banktransfer_paynow_checkout_alert', $data);
    }
}
