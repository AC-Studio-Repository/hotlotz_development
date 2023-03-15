<?php

namespace App\Repositories;

use DB;

class CountryRepository
{
    public function __construct()
    {
    }

    public function getAllCountries()
    {
        return DB::table('countries')->pluck('name', 'id');
    }

    public function getAllCountriesForStripe()
    {
//        return DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'iso_3166_2');
        $getAllCountriesForStripe = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'iso_3166_2');
        $getAllCountriesForStripe['GB'] = $getAllCountriesForStripe['UK'];
        unset($getAllCountriesForStripe['UK']);
        return $getAllCountriesForStripe;

    }
}
