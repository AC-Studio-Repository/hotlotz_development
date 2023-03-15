<?php

namespace App\Modules\BusinessSeller\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSeller extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'business_seller';
}
