<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Models\GeneralInfo;

class GeneralInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('general_info')->truncate();
        
        $rows = [
            [
                'key' => "strategic_partners_info"
            ],
            [
                'key' => "strategic_partners_banner"
            ],
            [
                'key' => "faq_info"
            ],
            [
                'key' => "faq_banner"
            ],
            [
                'key' => "professional_valuations_banner"
            ],
            [
                'key' => "whatwesell_info"
            ],
            [
                'key' => "whatwesell_banner"
            ]
        ];

        if (count($rows) > 0) {
            Seed::insertData(GeneralInfo::class, $rows);
        }
    }
}
