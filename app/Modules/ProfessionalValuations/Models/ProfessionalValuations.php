<?php

namespace App\Modules\ProfessionalValuations\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalValuations extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'professional_valuations_info';
}
