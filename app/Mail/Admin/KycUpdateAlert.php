<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycUpdateAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $customer;
    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'subject' => "KYC Update Alert",
            'client' => $this->customer->ref_no.'_'.$this->customer->fullname,
            'link' =>  config('app.admin_domain').route('customer.customers.show', $this->customer, false),
        ];
        \Log::channel('emailLog')->info('KycUpdateAlert data : '.print_r($data,true) );

        return $this->subject('KYC Update Alert')->view('emails.admin.kyc_update_alert', $data);
    }
}
