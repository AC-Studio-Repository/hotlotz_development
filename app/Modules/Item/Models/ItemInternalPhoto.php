<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Item\Models\Item;

class ItemInternalPhoto extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $table = 'item_internal_photos';

    public $incrementing = false;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
