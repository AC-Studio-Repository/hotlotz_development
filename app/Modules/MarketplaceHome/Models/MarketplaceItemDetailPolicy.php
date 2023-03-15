<?php

namespace App\Modules\MarketplaceHome\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceItemDetailPolicy extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'item_detail_policy';
}
