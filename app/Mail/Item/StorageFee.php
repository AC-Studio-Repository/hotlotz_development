<?php

namespace App\Mail\Item;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StorageFee extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #12
     *
     * @return void
     */
    public $items, $type;
    public function __construct($items, $type)
    {
        $this->items = $items;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        $data = [
            'subject' => "Storage Fee Notify Email",
            'items' => $this->items,
        ];

        if($this->type == 'first'){
            return $this->view('emails.item.storage_fee', $data);
        }

        if($this->type == 'second'){
            return $this->view('emails.item.storage_fee_second', $data);
        }        
    }
}
