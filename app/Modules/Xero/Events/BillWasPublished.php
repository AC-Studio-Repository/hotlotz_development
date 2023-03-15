<?php

namespace App\Modules\Xero\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BillWasPublished
{
    use Dispatchable, SerializesModels;

    public $invoice_id;

    public $auction_id;

    public function __construct($invoice_id, $auction_id)
    {
        $this->invoice_id = $invoice_id;
        $this->auction_id = $auction_id;
    }
}
