<?php

use Illuminate\Database\Seeder;

class CountryCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Empty the countries table
        DB::table('country_codes')->truncate();

        //Get all of the countries
        $country_codes = json_decode(file_get_contents(database_path().'/seeds/countries_by_callingcode.json'), true);
        foreach ($country_codes as $key => $code) {
            if($code['dialling_code'] == '+65') {
                DB::table('country_codes')->insert([
                    'country_code'      => $code['country_code'],
                    'country_name'      => $code['country_name'],
                    'dialling_code'      => $code['dialling_code'],
                    'order_by_status'      => 1
                ]);
            }else{
                DB::table('country_codes')->insert([
                    'country_code'      => $code['country_code'],
                    'country_name'      => $code['country_name'],
                    'dialling_code'      => $code['dialling_code']
                ]);
            }
        }
    }
}
