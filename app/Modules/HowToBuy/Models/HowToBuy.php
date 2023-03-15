<?php

namespace App\Modules\HowToBuy\Models;

use Illuminate\Database\Eloquent\Model;

class HowToBuy extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'how_to_buy';
}
