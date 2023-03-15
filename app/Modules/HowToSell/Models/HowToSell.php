<?php

namespace App\Modules\HowToSell\Models;

use Illuminate\Database\Eloquent\Model;

class HowToSell extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'how_to_sell';
}
