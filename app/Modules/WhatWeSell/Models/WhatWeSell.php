<?php

namespace App\Modules\WhatWeSell\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use App\Modules\Category\Models\Category;

class WhatWeSell extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'what_we_sell';

    public function category()
    {
        return $this->belongsTo(Category::class);
        // return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}
