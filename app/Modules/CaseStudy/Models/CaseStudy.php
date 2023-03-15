<?php

namespace App\Modules\CaseStudy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseStudy extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'case_study';
}

