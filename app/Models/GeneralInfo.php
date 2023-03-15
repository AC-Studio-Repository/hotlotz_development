<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralInfo extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'general_info';
}
