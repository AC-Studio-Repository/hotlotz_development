<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class SaleroomCustomerRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $customer, $auction_name, $items;
    public function __construct($customer, $auction_name, $items)
    {
        $this->customer = $customer;
        $this->auction_name = $auction_name;
        $this->items = $items;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'subject' => "Saleroom Customer Register",
            'link' => $this->verificationUrl($this->customer),
            'customer' => $this->customer,
            'auction_name' => $this->auction_name,
            'items' => $this->items,
        ];
        \Log::channel('emailLog')->info('link : '.$data['link']);

        return $this
            ->subject($data['subject'])
            ->view('emails.user.sr_customer_register', $data);
    }

    protected function verificationUrl($customer)
    {
        return URL::signedRoute(
            'verification.saleroom_customer.verify',
            [
                'id' => $customer->id,
                'hash' => sha1($customer->getEmailForVerification()),
            ]
        );
    }
}
