<?php

namespace App\Modules\OrderSummary\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Modules\OrderSummary\Models\OrderSummary as Order;

class OrderWasCreated
{
    use Dispatchable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
