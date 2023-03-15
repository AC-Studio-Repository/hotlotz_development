<?php

namespace App\Modules\Xero\Events\Invoice;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class MarketplaceInvoiceEvent
{
    use Dispatchable, SerializesModels;

    public $payload;

    public $only;

    public $date;

    public function __construct($payload, $only = null, $date = null)
    {
        $this->payload = $payload;
        $this->only = $only;
        $this->date = $date;
    }
}
