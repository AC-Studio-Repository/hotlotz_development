<?php

use App\Modules\CaseStudy\Models\CaseStudy;
use Illuminate\Database\Seeder;

class CaseStudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CaseStudy::class, 10)->create();
    }
}
