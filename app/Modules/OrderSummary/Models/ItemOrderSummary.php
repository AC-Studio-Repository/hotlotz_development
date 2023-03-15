<?php

namespace App\Modules\OrderSummary\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemOrderSummary extends Model
{
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public $table = 'item_order_summary';
}
