<?php

namespace App\Modules\MarketplaceHome\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceCollaborationBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'marketplace_collaboration_blogs';
}
