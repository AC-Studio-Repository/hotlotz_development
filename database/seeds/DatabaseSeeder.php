<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            TimezonesSeeder::class,
            CountriesSeeder::class,
            LifecyclesTableSeeder::class,
            CategoriesSeeder::class,
            TermsandconditionsSeeder::class,
            IncrementTableSeeder::class,
            EmailTemplateTableSeeder::class,
            // UserSeeder::class,
            ProfessionalValuationsTableSeeder::class,
            GeneralInfoSeeder::class,
            GapCountriesTableSeeder::class,
            CountryCodeSeeder::class,
            XeroItemTableSeeder::class,
            XeroTrackingTableSeeder::class
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
