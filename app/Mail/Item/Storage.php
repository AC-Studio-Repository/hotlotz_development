<?php

namespace App\Mail\Item;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Storage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #11
     *
     * @return void
     */
    public $items;
    public function __construct($items)
    {
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
            'subject' => "Collection Required",
            'items' => $this->items,
        ];

        return $this->view('emails.item.storage', $data);
    }
}
