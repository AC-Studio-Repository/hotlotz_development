<?php

namespace App\Modules\HomeContent\Models;

use Illuminate\Database\Eloquent\Model;

class HomeContentBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'home_content_blogs';
}
