<?php

namespace App\Modules\WhatsNewBidBarometer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsNewBidBarometer extends Model
{
    use SoftDeletes;

    protected $table = 'whats_new_bid_barometer';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dispatchesEvents = [
        // 'created' => WhatsNewBidBarometerCreatedEvent::class,
    ];
}
