<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripePayload extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $table = 'stripe_payloads';
}