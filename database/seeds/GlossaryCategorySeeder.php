<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Models\GlossaryCategory;

class GlossaryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('glossary_category')->truncate();
        
        $rows = [
            [
                'name' => "A - F"
            ],
            [
                'name' => "G - L"
            ],
            [
                'name' => "M - R"
            ],
            [
                'name' => "S - Z"
            ]
        ];

        if (count($rows) > 0) {
            Seed::insertData(GlossaryCategory::class, $rows);
        }
    }
}
