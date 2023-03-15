<?php

namespace App\Modules\Xero\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;

class InvoiceWasPublished
{
    use Dispatchable, SerializesModels;

    public $invoice_id;

    public $auction_id;

    public $local;

    public function __construct($invoice_id, $auction_id, $local)
    {
        $this->invoice_id = $invoice_id;
        $this->auction_id = $auction_id;
        $this->local = $local;
    }
}
