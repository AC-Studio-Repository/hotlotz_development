<?php

namespace App\Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class Report extends Model
{
	use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        // 'sale_dates' => 'array',
    ];

    // public $table = 'reports';
    
    protected function getTitle()
    {
        return $this->title;
    }
}
