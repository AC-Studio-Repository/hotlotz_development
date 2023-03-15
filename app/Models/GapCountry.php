<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GapCountry extends Model
{
    public $table = 'gap_countries';
    
    public static function getCountryByCountryCode($country_code)
    {
    	$country = GapCountry::where('code',$country_code)->select('name')->first();
    	$country_name = '';
    	if(isset($country)){
    		$country_name = $country->name;
    	}

    	return $country_name;
    }
}
