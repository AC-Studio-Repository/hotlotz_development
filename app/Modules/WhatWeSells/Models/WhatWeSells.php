<?php

namespace App\Modules\WhatWeSells\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Events\WhatWeSellsCreatedEvent;
// use App\Events\WhatWeSellsUpdatedEvent;
use App\Modules\Category\Models\Category;


class WhatWeSells extends Model
{
    use SoftDeletes;

    protected $table = 'what_we_sells';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $dispatchesEvents = [
        // 'created' => WhatWeSellsCreatedEvent::class,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected function getCategoriesForMarketplaceHeader()
    {
        $categories = WhatWeSells::orderBy('order')->get();
        $mp_categories = [];
        foreach ($categories as $key => $value) {
            $mp_categories[] = [
                'id' => $value->category_id,
                'title' => $value->category->name,
            ];
        }

        return $mp_categories;
    }
}
