<?php

namespace App\Modules\EmailTrigger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;


class EmailTrigger extends Model

{
    use SoftDeletes;

    // protected $table = 'categories';

    // protected $guarded = [
    //     'id', 'created_at', 'updated_at', 'deleted_at'
    // ];
}
