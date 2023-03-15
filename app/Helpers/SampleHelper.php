<?php

namespace App\Helpers;

use Arr;
use Str;
use Storage;
use DB;
use App\Modules\Item\Models\Item;

class SampleHelper
{
    public static function getRandomImage(){
        $images = [
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/Auctions/landing/auction_image_slider_3.jpg",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/Auctions/landing/auction_image_slider_2.jpg",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/Auctions/landing/auction_image_slider_1.png",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/auction/coming-soon/auction_3.png",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/auction/coming-soon/auction_2.png",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/auction/coming-soon/auction_1.png",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/auction/auction_3.png",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/auction/auction_2.png",
            "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/auction/auction_1.png",
        ];

        return Arr::random($images);
    }

    public static function getRandomBanner(){
        $images = [
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/banner/auction_image_slider_3.jpg",
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/banner/auction_image_slider_2.jpg",
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/banner/auction_image_slider_1.png",
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/banner/Auction_Main_Banner_2.png",
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/banner/Auction_Main_Banner_1.png",
        ];

        return Arr::random($images);
    }

    public static function getRandomAuctionImage(){
        $images = [
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/auctions/auction_3.png",
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/auctions/auction_2.png",
            "https://s3-ap-southeast-1.amazonaws.com/dev.hotlotz.com/public/auctions/auction_1.png",
        ];

        return Arr::random($images);
    }

    public static function getMenuAuction() {
        $menu = collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Auction',
                'url' => ''
            ]
        ]);
        return $menu;
    }

    public static function getMenuMarketplace() {
        $menu = collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Marketplace',
                'url' => ''
            ]
        ]);
        return $menu;
    }

    public static function getMenuService() {
        $menu = collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Services',
                'url' => ''
            ]
        ]);
        return $menu;
    }

    public static function getMenuWhatWeSell() {
        $menu = collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Services',
                'url' => ''
            ],
            [
                'name' => 'What We Sell',
                'url' => route('services.what-we-sell')
            ]
        ]);
        return $menu;
    }

    public static function getMenuDiscover()
    {
        /* if (\Request::route()->getName(
            "discover.about-us" ||
            "discover.how-to-buy" ||
            "discover.how-to-sell" ||
            "discover.location" ||
            "discover.team" ||
            "discover.partners" ||
            "discover.articles-events" ||
            "event.detail" ||
            "discover.faq" ||
            "iscover.glossary"
        )) { */
            $menu = collect([
                [
                    'name' => 'Home',
                    'url' => route('home')
                ],
                [
                    'name' => 'Discover',
                    'url' => ''
                ]
            ]);
            return $menu;
        // }
    }

    public static function getMenuDiscoverEvent()
    {
        $menu = collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Discover',
                'url' => ''
            ],
            [
                'name' => 'Media Coverage & Events',
                'url' => route('discover.articles-events')
            ]
        ]);
        return $menu;
    }

    public static function getMenuFooter() {
        $menu = collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ]
        ]);
        return $menu;
    }

    public static function getCaseStudy()
    {
        if (\Request::route()->getName('services.private-collection' || 'sell-luxury' || 'location')) {
            $items = collect([
                [

                    'title' => 'Singaporean Collection',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                    'link' => 'CASE STUDY',
                    'type' => 'singapore'
                ],
                [
                    'title' => 'QKL',//'47 Dog Street',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                    'link' => 'CASE STUDY',
                    'type' => 'qkl'
                ],
                [
                    'title' => '26 Everton Road',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                    'link' => 'CASE STUDY',
                    'type' => 'everton'
                ]
            ]);

            return $items;
        }
        if (\Request::route()->getName('home-content')) {
            $items = collect([
                [
                    'title' => 'QKL',//'47 Dog Street',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                    'link' => 'CASE STUDY',
                    'type' => 'qkl'
                ],
                [
                    'title' => '26 Everton Road',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                    'link' => 'CASE STUDY',
                    'type' => 'everton'
                ]
            ]);

            return $items;
        } else {
            $items = collect([
                [
                    'title' => 'Home Content Auctions',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                    'link' => 'Find Out More'
                ],
                [
                    'title' => 'Professional Valuation',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                    'link' => 'Find Out More'
                ]
            ]);
            return $items;
        }
    }

    public static function marketPlaceItems($id=0)
    {
        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_, Item::_CLEARANCE_])
            ->whereNotIn('items.id', [$id])
            ->leftJoin('item_images', function ($join) {
                $join->on('item_images.id', '=', DB::raw('
                    (SELECT item_images.id FROM item_images
                    WHERE item_images.item_id = items.id
                    and item_images.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->leftJoin('item_lifecycles', function ($join) {
                $join->on('item_lifecycles.id', '=', DB::raw('
                    (SELECT item_lifecycles.id FROM item_lifecycles
                    WHERE item_lifecycles.item_id = items.id
                    and item_lifecycles.type IN ("'.Item::_MARKETPLACE_.'","'.Item::_CLEARANCE_.'")
                    and item_lifecycles.type = LOWER(items.lifecycle_status)
                    and item_lifecycles.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status')
            ->orderBy('item_lifecycles.entered_date', 'DESC')
            ->limit(15)
            ->get();

        $data = [];
        if (!$marketplaceItems->isEmpty()) {
            foreach ($marketplaceItems as $key => $value) {
                $data[] = [
                    'item_id' => $value->id,
                    'photoPath' => $value->file_path,
                    'itemTitle' => $value->title,
                    'priceStatus' => 'FIXED PRICE',
                    'minPrice' => number_format($value->price, 2),
                    'maxPrice' => number_format($value->price, 2),
                    'itemCurrency' => 'SGD',
                    'favourite' => 0
                ];
            }
        }

        return $data;
    }

    public static function nextItems()
    {
        $marketItem = collect([
            [
                'imgPath' => 'ecommerce/images/common/next.png',
                'cardTitleTime' => 'TIMED AUCTION',
                'cardTitle' => 'CHINESE WORKS OF ART FROM THE COLLECTION OF QUEK KIOK LEE',
                'slogon' => 'This is a single owner collection',
                'address' => 'BIDDING ENDS ON MONDAY 25 NOVEMBER FROM 8PM (SGT) / 10AM (GMT)'
            ]
        ]);

        return $marketItem;
    }

    public static function valuationItems()
    {
        $items = collect([
            [
                'title' => 'Online',
                'content' => 'All our auctions are listed in our auction calendar, along with forthcoming sale highlights and all the latest auction news. Fully illustrated catalogues are available in the days leading up to the sale, and you can subscribe to receive email alerts when catalogues go live online.'
            ],
            [
                'title' => 'In Person',
                'content' => 'All our sales are open for public viewing in our Leyburn salerooms. Viewing times vary for each sale, so please check sale listings or the online catalogue for each sale.'
            ],
            [
                'title' => 'Catalogues',
                'content' => 'Printed catalogues can be purchased in our Leyburn salerooms or in our Harrogate office. Alternatively, you can subscribe to receive catalogues for the Art Sales, Modern and Contemporary Sales, and 20th Century Design Sales by post. Please contact Gussie Wood on +44 (0)1969 623780. Annual subscription - UK £55 (inc. p&P) Single catalogues – UK £20 (inc. p&p) Please enquire for catalogue prices for Europe and the rest of the world.'
            ]
        ]);

        return $items;
    }

    public static function getSearchMenu(){
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Search',
                'url' => '#'
            ]
        ]);
    }
}
