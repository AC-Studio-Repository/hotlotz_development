<?php

namespace App\Modules\OurTeam\Models;

use Illuminate\Database\Eloquent\Model;

class OurTeamInfo extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'our_team_info';
}
