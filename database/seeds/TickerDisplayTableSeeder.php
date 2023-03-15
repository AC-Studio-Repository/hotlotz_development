<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Modules\TickerDisplay\Models\TickerDisplay;

class TickerDisplayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticker_displays')->truncate();

        $rows = [
            [
                'id' => 1,
                'title' => "CLEARANCE ITEMS ADDED TO MARKETPLACE",
                'description' => "Make instant purchases in the Hotlotz Marketplace at fixed, GST-inclusive prices with no additional buyer's premium",
                'link' => "https://www.hotlotz.com/marketplace/category-list/clearance",
                'order' => 1,
            ],
            [
                'id' => 2,
                'title' => "THE APP ISN'T COMING BACK!",
                'description' => "hotlotz.com is fully mobile responsive so add our address into the web browser on your handheld device and bid on-the-go",
                'link' => null,
                'order' => 2,
            ],
            [
                'id' => 3,
                'title' => "GOING, GOING GONE!",
                'description' => "Our auction sell-thought rates are at an all-time high as global buyers go digital. Talk to us if you have items of quality to sell",
                'link' => null,
                'order' => 3,
            ],
            [
                'id' => 4,
                'title' => "NEW CATALOGUE RELEASED",
                'description' => "Designer & Luxury - February. Bidding closes on Sunday 21 February from 6pm onwards",
                'link' => "https://bid.hotlotz.com/auctions/7725/hotzlo10103",
                'order' => 4,
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(TickerDisplay::class, $rows);
        }
    }
}