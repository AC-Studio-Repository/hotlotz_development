<?php

namespace App\Modules\Xero\Events\Invoice;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AuctionInvoiceEvent
{
    use Dispatchable, SerializesModels;

    public $auction_id;

    public $item_number;

    public $date;

    public function __construct($auction_id, $item_number, $date = null)
    {
        $this->auction_id = $auction_id;
        $this->item_number = $item_number;
        $this->date = $date;
    }
}
