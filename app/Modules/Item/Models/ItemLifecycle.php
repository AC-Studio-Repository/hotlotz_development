<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;


class ItemLifecycle extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'item_lifecycles';

    //Statuses for item_lifecycles->action
    const _PROCESSING_ = "Processing";
    const _FINISHED_ = "Finished";
    const _SKIPPED_ = "Skipped";

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'reference_id');
    }
}
