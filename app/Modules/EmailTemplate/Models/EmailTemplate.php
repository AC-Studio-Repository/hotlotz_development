<?php

namespace App\Modules\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class EmailTemplate extends Model
{
	use SoftDeletes;


    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        // 'sale_dates' => 'array',
    ];

    public $table = 'email_templates';
    // public $incrementing = false;
    
    protected function getTitle()
    {
        return $this->title;
    }
}
