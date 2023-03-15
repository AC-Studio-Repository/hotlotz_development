<?php

namespace App\Modules\AuctionMainPage\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionResultsMain extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'auction_results_info';
}
