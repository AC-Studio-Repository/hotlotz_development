<?php

namespace App\Modules\TickerDisplay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Events\TickerDisplayCreatedEvent;
// use App\Events\TickerDisplayUpdatedEvent;


class TickerDisplay extends Model
{
    use SoftDeletes;

    protected $table = 'ticker_displays';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $dispatchesEvents = [
        // 'created' => TickerDisplayCreatedEvent::class,
    ];
}
