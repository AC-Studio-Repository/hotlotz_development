<?php

namespace App\Modules\BusinessSeller\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSellerBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'business_seller_blogs';
}
