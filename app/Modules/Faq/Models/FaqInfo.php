<?php

namespace App\Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;

class FaqInfo extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'faq_info';
}
