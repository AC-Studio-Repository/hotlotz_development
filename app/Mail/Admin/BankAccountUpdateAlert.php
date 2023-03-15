<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BankAccountUpdateAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $customer;
    public $info_data;
    public function __construct($customer, $info_data)
    {
        $this->customer = $customer;
        $this->info_data = $info_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'subject' => "Bank Account Update Alert",
            'client' => $this->customer->ref_no.'_'.$this->customer->fullname,
            'link' => config('app.admin_domain').route('customer.customers.show', ['customer'=>$this->customer], false),
            'info_data' => $this->info_data,
        ];
        \Log::channel('emailLog')->info('BankAccountUpdateAlert data : '.print_r($data,true) );

        return $this->view('emails.admin.bank_account_update_alert', $data);
    }
}
