<?php

namespace App\Modules\MediaResource\Models;

use Illuminate\Database\Eloquent\Model;

class MediaResource extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'media_resource';
}
