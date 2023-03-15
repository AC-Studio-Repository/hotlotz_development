<?php

namespace App\Modules\HomePage\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageMarketplaceBanner extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'homepage_marketplace_banners';
}
