<?php

namespace App\Modules\StrategicPartner\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicPartnerInfoBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'strategic_partners_info_blogs';
}
