<?php

namespace App\Modules\Careers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;

class Careers extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'careers_jobs';
}
