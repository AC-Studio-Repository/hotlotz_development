<?php

namespace App\Modules\MarketplaceMainBanner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;

class MarketplaceMainBanner extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'marketplace_main_banners';
}
