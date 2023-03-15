<?php

namespace App\Modules\WhatsNewArticleOne\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsNewArticleOne extends Model
{
    use SoftDeletes;

    protected $table = 'whats_new_article_one';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dispatchesEvents = [
        // 'created' => WhatsNewArticleOneCreatedEvent::class,
    ];
}
