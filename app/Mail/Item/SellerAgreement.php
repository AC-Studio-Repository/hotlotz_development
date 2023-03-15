<?php

namespace App\Mail\Item;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerAgreement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #4. Email Sent with link to seller agreement
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
            'subject' => "ACTION REQUIRED",
            'link' => config('app.url').'/my-paperwork/seller_agreement'
        ];
        \Log::channel('emailLog')->info('Seller Agreement link : '.$data['link']);

        return $this->view('emails.item.seller_agreement', $data);
    }
}
