<?php

namespace App\Modules\PrivateCollections\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateCollectionsBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'private_collections_blogs';
}
