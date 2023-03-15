<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Models\Increment;

class IncrementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('increments')->truncate();

        $rows = [
            [
                'id' => 1,
                'low' => null,
                'high' => 200,
                'increment' => 20,
            ],
            [
                'id' => 2,
                'low' => 200,
                'high' => 499,
                'increment' => 50,
            ],
            [
                'id' => 3,
                'low' => 500,
                'high' => 999,
                'increment' => 50,
            ],
            [
                'id' => 4,
                'low' => 1000,
                'high' => 1999,
                'increment' => 100,
            ],
            [
                'id' => 5,
                'low' => 2000,
                'high' => 5000,
                'increment' => 200,
            ],
            [
                'id' => 6,
                'low' => 5000,
                'high' => 20000,
                'increment' => 500,
            ],
            [
                'id' => 7,
                'low' => 20000,
                'high' => null,
                'increment' => 1000,
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(Increment::class, $rows);
        }
    }
}