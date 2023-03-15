<?php

namespace App\Modules\BlogArticle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BlogArticle extends Model
{
    use SoftDeletes;

    protected $table = 'blog_articles';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];
}
