<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Modules\MainBanner\Models\MainBanner;

class MainBannerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_banners')->truncate();

        $rows = [
            [
                'id' => 1,
                'main_title' => "'DESIGNER & LUXURY' CLOSING SOON",
                'sub_title' => "Browse the auction catalogue now. Bidding closes on Sunday 21 February from 6pm onwards",
                'file_name' => "AUK9JWJSFZNQlOiUESXgZ2wvgmiGgLfz6BVWCLwi.png",
                'file_path' => "homepage_banners/main/14/AUK9JWJSFZNQlOiUESXgZ2wvgmiGgLfz6BVWCLwi.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/main/14/AUK9JWJSFZNQlOiUESXgZ2wvgmiGgLfz6BVWCLwi.png",
                'position' => "right",
                'color' => "pink",
                'link' => "https://bid.hotlotz.com/auctions/7725/hotzlo10103",
                'link_name' => "VIEW CATALOGUE",
                'order' => 1,
                'inactive' => 0,
            ],
            [
                'id' => 2,
                'main_title' => "NEW - HOTLOTZ X PIERS BOURKE",
                'sub_title' => "Piers Bourke has created a Singapore version of his iconic stamp series exclusively for Hotlotz. These are only available from the Hotlotz Marketplace!",
                'file_name' => "3pcYTo29iVs3zuENOoUlqk8syaj2QdNGKoUCaqPe.png",
                'file_path' => "homepage_banners/main/17/3pcYTo29iVs3zuENOoUlqk8syaj2QdNGKoUCaqPe.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/main/17/3pcYTo29iVs3zuENOoUlqk8syaj2QdNGKoUCaqPe.png",
                'position' => "left",
                'color' => "turquoise",
                'link' => "https://www.hotlotz.com/marketplace/collaborations",
                'link_name' => "SEE MORE",
                'order' => 2,
                'inactive' => 0,
            ],
            [
                'id' => 3,
                'main_title' => "ASIAN COLLECTIBLES & WORKS OF ART",
                'sub_title' => "Our quarterly sale catalogue will be released this March and includes the final selection of pieces from the Quek Kiok Lee collection",
                'file_name' => "Bw3GHtilOcA8vDSclXPC6fROUnleEtJYNJJtyWlO.jpeg",
                'file_path' => "homepage_banners/main/18/Bw3GHtilOcA8vDSclXPC6fROUnleEtJYNJJtyWlO.jpeg",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/main/18/Bw3GHtilOcA8vDSclXPC6fROUnleEtJYNJJtyWlO.jpeg",
                'position' => "left",
                'color' => "navy",
                'link' => "https://www.hotlotz.com/auctions/forthcoming-detail/asian-collectibles-works-of-art-2021-02-17-135532",
                'link_name' => "FIRST LOOK",
                'order' => 3,
                'inactive' => 0,
            ],
            [
                'id' => 4,
                'main_title' => "WE SMASHED THE WORLD RECORD (NOT THE VASE!)",
                'sub_title' => "Read about how Hotlotz discovered and sold this rare bottle form vase, with a six-character Yongzheng (1722-35) mark to the base",
                'file_name' => "1E9RezbKaIEBthgLH7UMXAIZNN1GDMyrbODi8OOm.png",
                'file_path' => "homepage_banners/main/19/1E9RezbKaIEBthgLH7UMXAIZNN1GDMyrbODi8OOm.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/main/19/1E9RezbKaIEBthgLH7UMXAIZNN1GDMyrbODi8OOm.png",
                'position' => "right",
                'color' => "turquoise",
                'link' => "https://www.antiquestradegazette.com/news/2020/chinese-blue-and-white-vase-sets-auction-record-at-online-sale/",
                'link_name' => "LEARN MORE",
                'order' => 4,
                'inactive' => 0,
            ],
            [
                'id' => 5,
                'main_title' => "ANOTHER 100% SOLD OUT AUCTION",
                'sub_title' => "Our 'Designer & Luxury' auctions are gaining in popularity - read about this 'white glove sale' which saw every item successfully sold.",
                'file_name' => "0jSOF4RzLKurA8jzvhD9vVexhSsEm2HigaNPdxs4.png",
                'file_path' => "homepage_banners/main/20/0jSOF4RzLKurA8jzvhD9vVexhSsEm2HigaNPdxs4.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/main/20/0jSOF4RzLKurA8jzvhD9vVexhSsEm2HigaNPdxs4.png",
                'position' => "left",
                'color' => "navy",
                'link' => "https://blog.hotlotz.com/blog/post/another-100-sold-out-auction",
                'link_name' => "LEARN MORE",
                'order' => 5,
                'inactive' => 0,
            ],
            [
                'id' => 6,
                'main_title' => "SHE IS MORE - CHARITY AUCTION",
                'sub_title' => "IIX and Hotlotz collaborate to raise funds and celebrate International Women's Day",
                'file_name' => "qslWmgAuebppMX7qlJHRGhiFuswE6cTw7Iiv3v66.png",
                'file_path' => "homepage_banners/main/21/qslWmgAuebppMX7qlJHRGhiFuswE6cTw7Iiv3v66.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/main/21/qslWmgAuebppMX7qlJHRGhiFuswE6cTw7Iiv3v66.png",
                'position' => "left",
                'color' => "navy",
                'link' => "https://www.hotlotz.com/auctions/forthcoming-detail/she-is-more-charity-auction-2020-11-13-154059",
                'link_name' => "COMING SOON",
                'order' => 6,
                'inactive' => 0,
            ],
            [
                'id' => 7,
                'main_title' => "DESIGNER & LUXURY - ONE WOMAN'S WARDROBE",
                'sub_title' => "Another fantastic single-owner collection of desig...",
                'file_name' => "ZmC0ToTbLWltoJR4RWt2I3KrpGZ1RM6puYKyKS7g.png",
                'file_path' => "homepage_banners/main/22/ZmC0ToTbLWltoJR4RWt2I3KrpGZ1RM6puYKyKS7g.png",
                'full_path' => "https://s3.ap-southeast-1.amazonaws.com/production.hotlotz.com/public/homepage_banners/main/22/ZmC0ToTbLWltoJR4RWt2I3KrpGZ1RM6puYKyKS7g.png",
                'position' => "left",
                'color' => "navy",
                'link' => "https://www.hotlotz.com/auctions/forthcoming-detail/designer-luxury-one-womans-wardrobe-2021-02-07-134828",
                'link_name' => "FIRST LOOK",
                'order' => 7,
                'inactive' => 0,
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(MainBanner::class, $rows);
        }
    }
}