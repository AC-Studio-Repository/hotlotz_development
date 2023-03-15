<?php

namespace App\Modules\MarketplaceBanner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Events\MarketplaceBannerCreatedEvent;
// use App\Events\MarketplaceBannerUpdatedEvent;


class MarketplaceBanner extends Model
{
    use SoftDeletes;

    protected $table = 'marketplace_banners';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $dispatchesEvents = [
        // 'created' => MarketplaceBannerCreatedEvent::class,
    ];
}
