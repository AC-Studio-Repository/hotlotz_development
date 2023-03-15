<?php

namespace App\Modules\LocationCms\Models;

use Illuminate\Database\Eloquent\Model;

class LocationCms extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'location_cms';
}
