<?php

namespace App\Modules\MarketplaceCms\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceCmsBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'marketplace_cms_blogs';
}
