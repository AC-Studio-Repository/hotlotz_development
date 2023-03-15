<?php

namespace App\Modules\HotlotzConcierge\Models;

use Illuminate\Database\Eloquent\Model;

class HotlotzConcierge extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'hotlotz_concierge';
}
