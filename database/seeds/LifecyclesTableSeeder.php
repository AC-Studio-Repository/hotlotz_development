<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Models\Lifecycle;
use Illuminate\Support\Str;

class LifecyclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lifecycles')->truncate();
        
        $rows = [
            [
                'id' => 1,
                'name' => 'Double all',
                'level1' => 'Auction 1',
                'level2' => 'Auction 2',
                'level3' => 'MarketPlace',
                'level4' => 'Clearance',
                'level5' => 'Storage',
                'description' => '',
            ],
            [
                'id' => 2,
                'name' => 'Double MP only',
                'level1' => 'Auction 1',
                'level2' => 'Auction 2',
                'level3' => 'MarketPlace',
                'level4' => 'Storage',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 3,
                'name' => 'Double Clearance only',
                'level1' => 'Auction 1',
                'level2' => 'Auction 2',
                'level3' => 'Clearance',
                'level4' => 'Storage',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 4,
                'name' => 'Double only',
                'level1' => 'Auction 1',
                'level2' => 'Auction 2',
                'level3' => 'Storage',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 5,
                'name' => 'Single all',
                'level1' => 'Auction 1',
                'level2' => 'MarketPlace',
                'level3' => 'Clearance',
                'level4' => 'Storage',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 6,
                'name' => 'Single MP only',
                'level1' => 'Auction 1',
                'level2' => 'MarketPlace',
                'level3' => 'Storage',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 7,
                'name' => 'Single Clearance only',
                'level1' => 'Auction 1',
                'level2' => 'Clearance',
                'level3' => 'Storage',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 8,
                'name' => 'Single only',
                'level1' => 'Auction 1',
                'level2' => 'Storage',
                'level3' => '',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 9,
                'name' => 'MP all',
                'level1' => 'MarketPlace',
                'level2' => 'Clearance',
                'level3' => 'Storage',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 10,
                'name' => 'MP only',
                'level1' => 'MarketPlace',
                'level2' => 'Storage',
                'level3' => '',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 11,
                'name' => 'Clearance only',
                'level1' => 'Clearance',
                'level2' => 'Storage',
                'level3' => '',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 12,
                'name' => 'Private Sale',
                'level1' => 'Private Sale',
                'level2' => 'Storage',
                'level3' => '',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
            [
                'id' => 13,
                'name' => 'Storage',
                'level1' => 'Storage',
                'level2' => '',
                'level3' => '',
                'level4' => '',
                'level5' => '',
                'description' => '',
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(Lifecycle::class, $rows);
        }
    }
}