<?php

namespace App\Modules\ShippingAndStorage\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAndStorageBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'shipping_and_storage_blogs';
}
