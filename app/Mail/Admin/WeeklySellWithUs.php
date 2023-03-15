<?php

namespace App\Mail\Admin;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WeeklySellWithUs extends Mailable
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

        $from = Carbon::parse(date('Y-m-d'))->previous('Tuesday');
        $from = $from->hour(9)->minute(1);

        $to = Carbon::parse(date('Y-m-d'));
        $to = $to->hour(9);

        $data = [
            'subject' => "Weekly Sell With Us",
            'items' => $this->items,
            'from' => $from,
            'to' => $to,
        ];

        return $this->subject('Weekly Sell With Us')->view('emails.admin.weekly_sell_with_us', $data);
    }
}