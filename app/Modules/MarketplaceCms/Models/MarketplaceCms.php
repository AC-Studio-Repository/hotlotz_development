<?php

namespace App\Modules\MarketplaceCms\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceCms extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'marketplace_cms';
}
