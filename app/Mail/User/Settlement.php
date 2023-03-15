<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Settlement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #9
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'subject' => "Settlement Confirmation",
            'link' => url( config('app.url').route('my-settlement', [], false) ),
        ];

        return $this->view('emails.user.settlement', $data);
    }
}
