<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Utility;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use GAP\Configuration as Configuration;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class NHelpers
{
    public static function obj_arr($var)
    {
        $arr    = array();
        $i      = 0;
        foreach ($var as $split) {
            foreach ($split as $key => $value) {
                $arr[$i][$key]  = $value;
            }
            $i++;
        }
        return $arr;
    }

    // ========================
    // current date time ===
    // ========================
    public static function timestamp_at($format = 'Y-m-d H:i:s')
    {
        // 2017-08-09 10:09:20
        return date($format);
    }

    // ===========================
    // current login user id ===
    // ===========================
    public static function get_user_id()
    {
        return (auth()->id() == null) ? '1' : auth()->id();
    }

    // ===========================
    // current login user name ===
    // ===========================
    public static function get_user_name()
    {
        return auth()->user()->name;
    }

    // ===========================
    // current login role id ===
    // ===========================
    public static function get_user_role_id()
    {
        return DB::table('role_user')
            ->where('role_user.user_id', '=', NHelpers::get_user_id())
            ->select('role_user.role_id')
            ->first()->role_id;
    }

    // ===========================
    // check current login role ==
    // ===========================
    public static function has_role($role)
    {
        return \Auth::user()->hasRole($role);
    }

    // =======================================
    // updated timestamp ===
    // =======================================
    public static function updated_at()
    {
        // ['updated_at' => date('Y-m-d H:i:s')]
        return self::action_at('updated');
    }

    // =======================================
    // created, updated timestamp ===
    // =======================================
    public static function created_updated_at()
    {
        // ['created_at' => date('Y-m-d H:i:s')]
        // +
        // ['updated_at' => date('Y-m-d H:i:s')]
        return self::action_at('created') + self::action_at('updated');
    }

    public static function action_at($action)
    {
        return [$action.'_at' => self::timestamp_at()];
    }

    // ==============================
    // created timestamp and user ===
    // ==============================
    public static function created_at_by()
    {
        // ['created_at' => date('Y-m-d H:i:s'), 'created_by' => ID]
        return self::action_at_by('created');
    }

    // ==============================
    // updated timestamp and user ===
    // ==============================
    public static function updated_at_by()
    {
        // ['updated_at' => date('Y-m-d H:i:s'), 'updated_by' => ID]
        return self::action_at_by('updated');
    }

    // =======================================
    // created, updated timestamp and user ===
    // =======================================
    public static function created_updated_at_by()
    {
        // ['created_at' => date('Y-m-d H:i:s'), 'created_by' => ID]
        // +
        // ['updated_at' => date('Y-m-d H:i:s'), 'updated_by' => ID]
        return self::action_at_by('created') + self::action_at_by('updated');
    }

    // ==============================
    // deleted timestamp and user ===
    // ==============================
    public static function deleted_at_by()
    {
        return self::action_at_by('deleted');
    }

    public static function reset_deleted_at_by()
    {
        return [
            'deleted_by' => null,
            'deleted_at' => null
        ];
    }

    public static function action_at_by($action)
    {
        return [$action.'_at' => self::timestamp_at(), $action.'_by' => self::get_user_id()];
    }

    /*
     * @param1 date time format, @param2 date time string
     */
    public static function format_dt($format, $dt)
    {
        return date($format, strtotime($dt));
    }

    public static function db_raw_date_time_format_from_mysql($column, $name)
    {
        return DB::raw(NHelpers::date_time_format_from_mysql($column).' as '.$name);
    }

    public static function date_time_format_from_mysql($column_name)
    {
        // return 'date_format('.$column_name.', "%d-%m-%Y, %h:%i%p")';  // 06-10-2017, 06:01 PM
        return self::date_time_format_all($column_name, '')->date_time_format_from_mysql;
    }

    public static function date_format_from_mysql($column_name)
    {
        return self::date_time_format_all($column_name, '')->date_format_from_mysql;
    }

    public static function time_format_from_mysql($column_name)
    {
        return self::date_time_format_all($column_name, '')->time_format_from_mysql;
    }

    public static function date_time_format_from_php($date)
    {
        // return date('d-m-Y, h:i A', strtotime($date));   // 06-10-2017, 06:01 PM
        return self::date_time_format_all('', $date)->date_time_format_from_php;
    }

    public static function date_format_from_php($date)
    {
        // return date('d-m-Y', strtotime($date));   // 01-09-2017
        return self::date_time_format_all('', $date)->date_format_from_php;
    }

    public static function time_format_from_php($date)
    {
        // return date('d-m-Y', strtotime($date));   // 06:01 PM
        return self::date_time_format_all('', $date)->time_format_from_php;
    }

    public static function sendAPI($api_url, $data)
    {
        Log::info('API');

        stream_context_set_default(['http' => ['method' => 'POST']]);
        $Header  = @get_headers($api_url);

        # check url is existed or not
        if (strpos($Header[0], "200") || strpos($Header[0], "302")) {

            # call api
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, "data=".json_encode($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 400);
            $content = trim(curl_exec($ch));
            $error = curl_error($ch);
            curl_close($ch);

            if ($content !== false) {
                // $content = json_decode( $content, true );
                Log::info("API - return " . $content);

                return $content;
            }
            Log::error("API - error " . $error);
        } else {
            Log::error("API - URL not found " . $api_url);
        }
        return false;
    }

    // Path of public/
    public static function get_public_path()
    {
        return public_path();
    }

    // Path of application root
    public static function get_base_path()
    {
        return base_path();
    }

    // Path of storage/
    public static function get_storage_path()
    {
        return storage_path();
    }

    // Path of app/
    public static function get_app_path()
    {
        return app_path();
    }

    public static function dir_exists($path, $create = 1)
    {
        if (!File::isDirectory($path)) {
            if ($create == 1) {
                File::makeDirectory($path, 0777, true, true);
            }
            return true;
        }
        return false;
    }

    public static function clean_string($string, $replace = '')
    {
        return preg_replace('/[^A-Za-z0-9\-\s]/', $replace, $string); // Removes special chars.
    }

    public static function createJWT($payload, $header = null)
    {
        if ($header == null) {
            // Create token header as a JSON string
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        }

        $payload = ['id' => "1",'name' => "MayCho",'email' => "maycho@hotlotz.com",'password' => "password"];
        // Create token payload as a JSON string
        $payload = json_encode($payload);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'secret', true); //abC123!

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

    public static function formatDateTime($datetime)
    {
        $dmyt = explode(' ', $datetime);
        $dm = explode(' ', $dmyt[0]);
        $yt = explode(' ', $dmyt[1]);
        $day = $dm[0];
        $month = $dm[1];
        $year = $yt[1];
        $t = $yt[2];
        $t = explode(':', $t);
        $hour = $t[0];
        $minute = $t[1];
        if ($yt[3] == 'PM') {
            $hour = (int)$hour + 12;
        }
        $month = Carbon::parse('1 '.$month)->month;
        $formatted = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.'00.00';
        return $formatted;
    }

    public static function formatDateForShow($date_time)
    {
        $date = $date_time;
        $dt = explode(' ', $date);
        $d = explode('-', $dt[0]);
        $t = explode(':', $dt[1]);
        $year = $d[0];
        $month = $d[1];
        $day = 1;
        $hour = $t[0];
        $minute = $t[1];
        $second = $t[2];
        $dt_instance =  Carbon::create($year, $month, $day, $hour, $minute, $second);
        // return $dt_instance->format('d F, Y h:m A');
        return $dt_instance->format('Y-m-d h:m A');
    }

    public static function getGmtDateTime($datetime)
    {
        // $current_tz = config('app.timezone');
        // echo "Current TZ : ".$current_tz."<br />";
        // $server_tz = new \DateTimeZone($current_tz);
        // $mmt = new \DateTimeZone('Asia/Rangoon');

        $Date = new \DateTime($datetime);
        // echo "Server TZ : ".$Date->format("Y-m-d H:i:s")."<br />";

        $gmt = new \DateTimeZone('UTC');
        $Date->setTimezone($gmt);
        $gmt_datetime = $Date->format("Y-m-d H:i:s");
        // echo  "UTC : ".$gmt_datetime."<br />";

        // dd($gmt_datetime);
        return $gmt_datetime;
    }

    public static function getGapClientId()
    {
        return "a20b82bf-5262-464d-b961-a7a200c35d3c";
    }

    public static function getGapConfig()
    {
        $config = Configuration::getDefaultConfiguration()
              ->setUsername('maychothet@nexlabs.co')
              ->setPassword('hotlotz@december19');
        // ->setUsername('floriewinmyint@nexlabs.co')
        // ->setPassword('hotlotz@december19');

        return $config;
    }

    public static function changeJsonDateTimeToPhpDateTime($json_date)
    {
        preg_match('/\/Date\(([0-9]+)(\-[0-9]+)?/', $json_date, $time);
        // print_r($time); echo "<br>";

        // remove milliseconds from timestamp
        $ts = $time[1] / 1000;

        // Define Time Zone if exists
        // $tz = isset($time[2]) ? new \DateTimeZone($time[2]) : null;
        $tz = new \DateTimeZone('Asia/Singapore');
        // print_r($tz); echo "<br>";

        // Create a new date object from your timestamp
        // note @ before timestamp
        // and don't specify timezone here as it will be ignored anyway
        $dt = new \DateTime();
        $dt->setTimestamp($ts);

        // If you'd like to apply timezone for whatever reason
        if ($tz) {
            $dt->setTimezone($tz);
        }

        // Print your date
        $end_time_utc = $dt->format('Y-m-d H:i:s');
        // dd( $end_time_utc );
        return $end_time_utc;
    }

    public static function getSalutations()
    {
        $salutations = [
            'R_N_S' => 'Rather not say',
            'Dr' => 'Dr',
            'Mr' => 'Mr',
            'Mrs' => 'Mrs',
            'Ms' => 'Ms',
            'Miss' => 'Miss'
        ];
        return $salutations;
    }

    public static function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public static function paginate(Collection $results, $pageSize)
    {
        $page = Paginator::resolveCurrentPage('page');

        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }
}
