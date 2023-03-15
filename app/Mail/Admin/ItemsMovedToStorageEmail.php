<?php

namespace App\Mail\Admin;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ItemsMovedToStorageEmail extends Mailable
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
        $from = Carbon::parse(date('Y-m-d'))->yesterday()->hour(9);
        $to = Carbon::parse(date('Y-m-d'))->hour(9);

        $data = [
            'subject' => "Daily Update for Items moved to Storage",
            'items' => $this->items,
            'from' => $from,
            'to' => $to,
        ];

        return $this->subject('Daily Update for Items moved to Storage')->view('emails.admin.items_moved_to_storage_email', $data);
    }
}