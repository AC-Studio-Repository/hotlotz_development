<?php

namespace App\Modules\MarketplaceHome\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceCollabrationInfo extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'marketplace_collabration_info';
}
