<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class Welcome extends Mailable
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
            'subject' => "Welcome to the NEW Hotlotz",
            'link' => $this->verificationUrl($this->customer),
            'customer' => $this->customer,
        ];
        \Log::channel('emailLog')->info('welcome link : '.$data['link']);
        
        return $this
            ->subject($data['subject'])
            ->view('emails.user.welcome', $data);
    }

    protected function verificationUrl($customer)
    {
        return URL::signedRoute(
            'verification.verify',
            [
                'id' => $customer->id,
                'hash' => sha1($customer->getEmailForVerification()),
            ]
        );
    }
}
