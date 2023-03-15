<?php

namespace App\Modules\InternalAdvert\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalAdvert extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'internal_advert';
}

