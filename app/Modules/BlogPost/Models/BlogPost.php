<?php

namespace App\Modules\BlogPost\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BlogPost extends Model
{
    use SoftDeletes;

    protected $table = 'blog_posts';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];
}
