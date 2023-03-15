<?php

namespace App\Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Customer\Models\Customer;


class CustomerNote extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $table = 'customer_notes';

}
