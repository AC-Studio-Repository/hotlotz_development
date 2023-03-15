<?php

use App\Modules\InternalAdvert\Models\InternalAdvert;
use Illuminate\Database\Seeder;

class InternalAdvertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(InternalAdvert::class, 10)->create();
    }
}
