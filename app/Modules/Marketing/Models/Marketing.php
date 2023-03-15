<?php

namespace App\Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class Marketing extends Model
{
	use SoftDeletes;


    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        // 'sale_dates' => 'array',
    ];

    // public $table = 'marketings';
    // public $incrementing = false;
    
    protected function getTitle()
    {
        return $this->title;
    }
}
