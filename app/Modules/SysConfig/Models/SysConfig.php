<?php

namespace App\Modules\SysConfig\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class SysConfig extends Model
{
	use SoftDeletes;
    
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        // 'sale_dates' => 'array',
    ];

    public $table = 'sys_configs';
    // public $incrementing = false;

    public static function getTodayOpenTime()
    {
    	$today_day = strtolower( date('l') );
        $start_time = $today_day."_start_time";
        $end_time = $today_day."_end_time";
        $closed_day = 'is_closed_'.$today_day;

        $sysconfig = SysConfig::select($start_time, $end_time, $closed_day)->first();

        $today_open_time = 'Open Today 10:00AM - 8:00PM';
        if($sysconfig->$closed_day == 'Y'){
            $today_open_time = 'Closed Today';
        }

        if($sysconfig->$closed_day != 'Y'){
            $start = (isset($sysconfig->$start_time) && $sysconfig->$start_time != null)?$sysconfig->$start_time:'10:00AM';
            $end = (isset($sysconfig->$end_time) && $sysconfig->$end_time != null)?$sysconfig->$end_time:'8:00PM';

            $start = str_replace(":00 ", "", $start);
            $end = str_replace(":00 ", "", $end);

            $today_open_time = 'Open ' . $start .' - '. $end . ' Today';
        }

        return $today_open_time;
    }

    public static function getOpeningTime($day)
    {
        $start_time = $day."_start_time";
        $end_time = $day."_end_time";
        $closed_day = 'is_closed_'.$day;

        $sysconfig = SysConfig::select($start_time, $end_time, $closed_day)->first();

        $openingTime = "";

        if($sysconfig->$closed_day == 'Y'){
            $openingTime = 'Closed';
        }

        if($sysconfig->$closed_day != 'Y'){
            $start = (isset($sysconfig->$start_time) && $sysconfig->$start_time != null)?$sysconfig->$start_time:'10:00AM';
            $end = (isset($sysconfig->$end_time) && $sysconfig->$end_time != null)?$sysconfig->$end_time:'8:00PM';

            $start = str_replace(":00 ", "", $start);
            $end = str_replace(":00 ", "", $end);

            $openingTime = $start .' - '. $end;
        }

        return $openingTime;
    }
}
