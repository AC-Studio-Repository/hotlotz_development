<?php

namespace App\Modules\WhatWeSells\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class WhatWeSellHighlight extends Model
{
    use SoftDeletes;

    protected $table = 'what_we_sell_highlights';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];
}
