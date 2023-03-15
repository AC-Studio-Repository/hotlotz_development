<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;

    #15
    /**
     * Item(s) name array
     *
     * @var array
     */
    public $itemNames;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($itemNames)
    {
        $this->itemNames = $itemNames;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         $data = [
            'subject' => "Payment Receipt",
            'itemNames' => $this->itemNames
        ];

        return $this
            ->subject($data['subject'])
            ->view('emails.user.payment_receipt', $data);
    }
}
