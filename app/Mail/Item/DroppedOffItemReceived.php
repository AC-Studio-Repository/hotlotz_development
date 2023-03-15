<?php

namespace App\Mail\Item;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DroppedOffItemReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #3. Email with link receipt when items are dropped off
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
            'subject' => "Item Received",
            'link' => url( config('app.url').route('my-saleroom', [], false) )
        ];

        return $this->view('emails.item.dropoff_receipt', $data);
    }
}
