<?php

namespace App\Modules\OrderSummary\Events;

use App\Modules\Item\Models\Item;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrderWasCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
