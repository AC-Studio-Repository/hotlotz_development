<?php

namespace App\Modules\Xero\Events\Settlement;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class PrivateSaleSettlementEvent
{
    use Dispatchable, SerializesModels;

    public $item_number;

    public $date;

    public function __construct($item_number, $date = null)
    {
        $this->item_number = $item_number;
        $this->date = $date;
    }
}
