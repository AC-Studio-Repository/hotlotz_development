<?php

namespace App\Mail\Item;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerAgreementConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #5
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
            'subject' => "Seller Agreement Confirmation",
            'link' => config('app.url').'/my-paperwork/sales-contracts',
        ];
        \Log::channel('emailLog')->info('Seller Agreement Confirmation link : '.$data['link']);

        return $this->view('emails.item.seller_agreement_confirmation', $data);
    }
}
