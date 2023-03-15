<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Item\Models\Item;

class ItemHistory extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $table = 'item_histories';

    public $incrementing = false;
}
