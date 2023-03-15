<?php

namespace App\Modules\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class Marketplace extends Model
{
    use SoftDeletes;

    public static function getDiffTime($item){
        $left_time = null;
        if( in_array($item->type,['marketplace','clearance']) && $item->entered_date != null){
            $entered_date = strtotime($item->entered_date);
            $expired_date = date('Y-m-d', strtotime('+'.intval($item->period).' day', $entered_date));

            $date1 = new \DateTime("now");
            $date2 = new \DateTime($expired_date);

            $interval = date_diff($date1, $date2);
            $left_time = $interval->format("%a Days, %H:%I:%S Time.");
        }
        return $left_time;
    }
}
