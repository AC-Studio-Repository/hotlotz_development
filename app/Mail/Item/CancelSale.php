<?php

namespace App\Mail\Item;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelSale extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $item;
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'subject' => "Cancel Sale Item",
            'item' => $this->item,
        ];

        return $this->view('emails.item.cancel_sale', $data);
    }
}
