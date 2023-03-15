<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycIndividualSellerEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
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
        $next_two_date = date('F j, Y',strtotime("+2 days"));
        $data = [
            'subject' => "Requesting Additional Information",
            'link' =>  url( config('app.url').route('my-additional-info', [], false) ),
            'client' => $this->customer->firstname.' '.$this->customer->lastname,
            'ref_no' => $this->customer->ref_no,
            'next_two_date' => $next_two_date,
        ];
        \Log::channel('emailLog')->info('KycIndividualSellerEmail data : '.print_r($data,true) );

        return $this->subject('Requesting Additional Information')->view('emails.user.kyc_individual_seller_email', $data);
    }
}
