<?php

namespace App\Modules\Policy\Models;

use Illuminate\Database\Eloquent\Model;
use Hash;

class Policy extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'policies';
}
