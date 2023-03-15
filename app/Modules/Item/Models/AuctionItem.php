<?php

namespace App\Modules\Item\Models;

use App\Modules\Auction\Models\Auction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionItem extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'sr_lot_data' => 'array',
    ];

    public $table = 'auction_items';

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }
}
