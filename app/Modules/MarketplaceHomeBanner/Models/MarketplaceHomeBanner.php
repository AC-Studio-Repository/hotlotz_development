<?php

namespace App\Modules\MarketplaceHomeBanner\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceHomeBanner extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'marketplace_home_banners';
}
