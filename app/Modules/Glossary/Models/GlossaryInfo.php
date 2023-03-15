<?php

namespace App\Modules\Glossary\Models;

use Illuminate\Database\Eloquent\Model;
use Hash;
use App\Models\GlossaryCategory;

class GlossaryInfo extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'glossary_info';
}
