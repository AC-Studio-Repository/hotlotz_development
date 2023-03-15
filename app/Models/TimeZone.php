<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    public $table = 'time_zones';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * Indicates if the model should use soft deletes.
     *
     * @var bool $softDelete
     */
    protected $softDelete = false;
}
