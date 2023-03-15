<?php

namespace App\Modules\MarketplaceHome\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceHomeSubstainableSourcing extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'marketplace_sustainable_sourcing_banners';
}
