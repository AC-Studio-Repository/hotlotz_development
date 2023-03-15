<?php

namespace App\Modules\AuctionCms\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionCms extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'auction_cms';
}
