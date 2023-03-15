<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Modules\MarketplaceMainBanner\Models\MarketplaceMainBanner;

class MarketplaceMainBannerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marketplace_main_banners')->truncate();

        $rows = [
            [
                'id' => 1,
                'caption' => null,
                'file_name' => "2LZ8J6P1trrInfakmn6dhwsA18cHVOjTYAnvQ4Lh.png",
                'file_path' => "marketplace_home_banners/1/2LZ8J6P1trrInfakmn6dhwsA18cHVOjTYAnvQ4Lh.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/marketplace_home_banners/1/2LZ8J6P1trrInfakmn6dhwsA18cHVOjTYAnvQ4Lh.png",
                'learn_more' => null,
                'order' => 1,
            ],
            [
                'id' => 2,
                'caption' => null,
                'file_name' => "HgSyTgbtvjGBXaaLp825cJ6Q6vXDcSFhWE8EH2cR.png",
                'file_path' => "marketplace_home_banners/3/HgSyTgbtvjGBXaaLp825cJ6Q6vXDcSFhWE8EH2cR.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/marketplace_home_banners/3/HgSyTgbtvjGBXaaLp825cJ6Q6vXDcSFhWE8EH2cR.png",
                'learn_more' => null,
                'order' => 2,
            ],
            [
                'id' => 3,
                'caption' => null,
                'file_name' => "qsGMFihVW6oaOjsVReevhtDZECVGdQfUPXgtEkdd.png",
                'file_path' => "marketplace_home_banners/4/qsGMFihVW6oaOjsVReevhtDZECVGdQfUPXgtEkdd.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/marketplace_home_banners/4/qsGMFihVW6oaOjsVReevhtDZECVGdQfUPXgtEkdd.png",
                'learn_more' => null,
                'order' => 3,
            ],
            [
                'id' => 4,
                'caption' => null,
                'file_name' => "4TYfHOp4yej9ZINkzEytTsa4hGiBO3PZpF7cBkfv.png",
                'file_path' => "marketplace_home_banners/5/4TYfHOp4yej9ZINkzEytTsa4hGiBO3PZpF7cBkfv.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/marketplace_home_banners/5/4TYfHOp4yej9ZINkzEytTsa4hGiBO3PZpF7cBkfv.png",
                'learn_more' => null,
                'order' => 4,
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(MarketplaceMainBanner::class, $rows);
        }
    }
}
