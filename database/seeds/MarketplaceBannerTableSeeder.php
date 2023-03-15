<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Modules\MarketplaceBanner\Models\MarketplaceBanner;

class MarketplaceBannerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marketplace_banners')->truncate();

        $rows = [
            [
                'id' => 1,
                'file_name' => "xbm13XHDSsN6wIgG5LNQsWC0Nf5rZGH0lnUVFacb.png",
                'file_path' => "homepage_banners/marketplace/5/xbm13XHDSsN6wIgG5LNQsWC0Nf5rZGH0lnUVFacb.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/marketplace/5/xbm13XHDSsN6wIgG5LNQsWC0Nf5rZGH0lnUVFacb.png",
                'order' => 1,
                'inactive' => 0,
            ],
            [
                'id' => 2,
                'file_name' => "drYqQVvnnF8zFb7h3wQztCv159KVXneOHbHvnKNE.png",
                'file_path' => "homepage_banners/marketplace/6/drYqQVvnnF8zFb7h3wQztCv159KVXneOHbHvnKNE.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/marketplace/6/drYqQVvnnF8zFb7h3wQztCv159KVXneOHbHvnKNE.png",
                'order' => 2,
                'inactive' => 0,
            ],
            [
                'id' => 3,
                'file_name' => "ECB4xMlxGycGEfh9wxdAxTchRs3D3izZMzNKLJNw.png",
                'file_path' => "homepage_banners/marketplace/7/ECB4xMlxGycGEfh9wxdAxTchRs3D3izZMzNKLJNw.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/marketplace/7/ECB4xMlxGycGEfh9wxdAxTchRs3D3izZMzNKLJNw.png",
                'order' => 3,
                'inactive' => 0,
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(MarketplaceBanner::class, $rows);
        }
    }
}