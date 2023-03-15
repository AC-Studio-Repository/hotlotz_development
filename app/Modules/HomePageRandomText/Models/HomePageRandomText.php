<?php

namespace App\Modules\HomePageRandomText\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;

class HomePageRandomText extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'homepage_random_text';
}
