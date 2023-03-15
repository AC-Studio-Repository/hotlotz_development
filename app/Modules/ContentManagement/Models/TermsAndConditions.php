<?php

namespace App\Modules\ContentManagement\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'termsandconditions';
}
