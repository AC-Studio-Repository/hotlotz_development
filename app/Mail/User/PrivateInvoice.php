<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrivateInvoice extends Mailable
{
    use Queueable, SerializesModels;

    #Ad-hoc inv
    /**
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'subject' => "New invoice",
            'link' => url( config('app.url').route('my-receipt', 'awaiting', [], false) ),
        ];

        return $this->view('emails.user.private_invoice', $data);
    }
}