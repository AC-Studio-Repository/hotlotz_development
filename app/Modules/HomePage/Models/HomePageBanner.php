<?php

namespace App\Modules\HomePage\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageBanner extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'homepage_banners';
}
