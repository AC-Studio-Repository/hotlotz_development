<?php

namespace App\Modules\AdminEmail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class AdminEmail extends Model
{
	use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        // 'sale_dates' => 'array',
    ];

    public $table = 'admin_emails';
    // public $incrementing = false;
}
