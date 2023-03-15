<?php

namespace App\Mail\Item;

use Cake\Chronos\Date;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsignmentUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #7
     *
     * @return void
     */

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $auction_results = isset($this->data['auction_results'])?$this->data['auction_results']:[];
        $mp_results = isset($this->data['mp_results'])?$this->data['mp_results']:[];
        $notifications = isset($this->data['notifications'])?$this->data['notifications']:[];

        $email_data = [
            'subject' => "Your Consignment Update",
            'date' => date('l j F, Y'),
            'auction_results' => $auction_results,
            'mp_results' => $mp_results,
            'notifications' => $notifications,
            'link' => route('my-bank')
        ];

        return $this->view('emails.item.consignment_update', $email_data);
    }
}
