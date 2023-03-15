<?php

namespace App\Modules\MarketplaceHome\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceCollaboration extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'marketplace_collaboration_banners';
}
