<?php

namespace App\Mail\Item;

use App\Modules\Customer\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use App\Modules\Item\Models\Item;

class SubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * #2. Confirmation email sent to user about items
     *
     * @return void
     */
    public $customer;
    public $items;

    public function __construct($customer, $items)
    {
        $this->customer = $customer;
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
            'subject' => "Submission Received",
            'customer' => $this->customer->fullname,
            'items' => $this->items,
            'link' => route('my-paperwork'),
        ];

        return $this
            ->subject($data['subject'])
            ->view('emails.item.submission_received', $data);
    }
}
