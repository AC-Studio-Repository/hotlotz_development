<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Item\Models\Item;

class ItemImage extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $table = 'item_images';

    public $incrementing = false;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getImagePathAttribute()
    {
        if($this->thumbnail_path){
            return $this->thumbnail_path;
        }

        if($this->full_path){
            return $this->full_path;
        }

        return asset('images/default.jpg');
    }
}
