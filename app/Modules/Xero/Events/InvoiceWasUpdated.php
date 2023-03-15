<?php

namespace App\Modules\Xero\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;

class InvoiceWasUpdated
{
    use Dispatchable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}
