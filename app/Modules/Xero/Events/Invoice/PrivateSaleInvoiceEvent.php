<?php

namespace App\Modules\Xero\Events\Invoice;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class PrivateSaleInvoiceEvent
{
    use Dispatchable, SerializesModels;

    public $item_number;
    public $buyer_id;
    public $price;
    public $buyer_premiun;
    public $date;
    
    public function __construct($item_number, $buyer_id, $price, $buyer_premiun, $date = null)
    {
        $this->item_number = $item_number;
        $this->buyer_id = $buyer_id;
        $this->price = $price;
        $this->buyer_premiun = $buyer_premiun;
        $this->date = $date;
    }
}
