<?php

namespace App\Modules\Careers\Models;

use Illuminate\Database\Eloquent\Model;

class CareersInfo extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'careers';
}
