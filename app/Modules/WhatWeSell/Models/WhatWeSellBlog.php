<?php

namespace App\Modules\WhatWeSell\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\WhatWeSell\Models\WhatWeSell;

class WhatWeSellBlog extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'what_we_sell_blogs';

    public function what_we_sell()
    {
        return $this->belongsTo(WhatWeSell::class);
        // return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}
