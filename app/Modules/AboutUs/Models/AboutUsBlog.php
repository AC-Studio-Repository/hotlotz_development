<?php

namespace App\Modules\AboutUs\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUsBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'about_us_blogs';
}
