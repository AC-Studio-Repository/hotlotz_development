<?php

namespace App\Modules\HomeContent\Models;

use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'home_content';
}
