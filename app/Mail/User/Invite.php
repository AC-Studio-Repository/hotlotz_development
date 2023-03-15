<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class Invite extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * #1. Account activation email sent
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
            'subject' => "Your Invitation",
            'link' => $this->verificationUrl($this->customer),
            'customer' => $this->customer,
        ];

        return $this
            ->subject($data['subject'])
            ->view('emails.user.invite', $data);
    }

    protected function verificationUrl($customer)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $customer->id,
                'hash' => sha1($customer->getEmailForVerification()),
            ]
        );
    }
}
