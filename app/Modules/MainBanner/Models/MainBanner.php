<?php

namespace App\Modules\MainBanner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Events\MainBannerCreatedEvent;
// use App\Events\MainBannerUpdatedEvent;


class MainBanner extends Model
{
    use SoftDeletes;

    protected $table = 'main_banners';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $dispatchesEvents = [
        // 'created' => MainBannerCreatedEvent::class,
    ];
}
