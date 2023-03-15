<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManualAuctionInvoice extends Mailable
{
    use Queueable, SerializesModels;

    #18
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
            'subject' => "Your auction invoice",
            'auction_name' => "Home & Decor - December" ,
            'link' => url(config('app.url') . route('my-receipt', 'awaiting', [], false)),
            'customer' => $this->customer,
        ];

        return $this
            ->subject($data['subject'])
            ->view('emails.user.manual_auction_invoice', $data);
    }
}
