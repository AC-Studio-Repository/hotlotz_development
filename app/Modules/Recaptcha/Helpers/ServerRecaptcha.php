<?php

namespace App\Modules\Recaptcha\Helpers;

use Illuminate\Support\Arr;

class ServerRecaptcha
{
    /**
     * Recaptcha v2 check response code status
     *
     * @param  string $recaptcha_response
     * @return boolean
     */
    public static function v2($recaptcha_response = '')
    {
        if (!setting('services.recaptcha.on_live')) {
            return true;
        }

        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = config('services.recaptcha.v2.secret_key');

        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha, true);

        if ($recaptcha['success']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Recaptcha v3 check response code status
     *
     * @param  string $recaptcha_response
     * @return boolean
     */
    public static function v3($recaptcha_response = '')
    {
        if (!setting('services.recaptcha.on_live')) {
            return true;
        }

        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = setting('services.recaptcha.v3.secret_key');

        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        if($recaptcha != null && isset($recaptcha->score)){
            if ($recaptcha->score >= (float) setting('services.recaptcha.score')) {
                return true;
            }
        }

        return false;

    }
}
