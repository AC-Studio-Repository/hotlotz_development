<?php

namespace App\Mail\Item;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class Confirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #2. Confirmation email sent to user about items
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
            'subject' => "Item Confirmation",
            'customer' => new Collection(),
            'items' => new Collection(),
        ];

        return $this->view('emails.item.confirmation', $data);
    }
}
