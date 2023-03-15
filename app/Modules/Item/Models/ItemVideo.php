<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Item\Models\Item;

class ItemVideo extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $table = 'item_videos';

    public $incrementing = false;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
