<?php

namespace App\Modules\SellWithUs\Models;

use Illuminate\Database\Eloquent\Model;

class SellWithUsBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'sell_with_us_blog';
}
