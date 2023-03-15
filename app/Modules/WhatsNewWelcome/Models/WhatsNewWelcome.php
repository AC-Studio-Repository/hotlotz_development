<?php

namespace App\Modules\WhatsNewWelcome\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsNewWelcome extends Model
{
    use SoftDeletes;

    protected $table = 'whats_new_welcome';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dispatchesEvents = [
        // 'created' => WhatsNewWelcomeCreatedEvent::class,
    ];
}
