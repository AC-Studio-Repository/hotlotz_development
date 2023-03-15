<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalesContractAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
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
            'subject' => "Sales Contract Alert",
            'items' => $this->items,
        ];
        \Log::channel('emailLog')->info('SalesContractAlert data : '.print_r($data,true) );

        return $this->subject('Sales Contract Alert')->view('emails.admin.sales_contract_alert', $data);
    }
}
