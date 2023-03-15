<?php

namespace App\Repositories;

use DB;
use Auth;
use App\Helpers\SampleHelper;
use Yajra\Datatables\Datatables;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Item\Models\ItemVideo;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Category\Models\Category;
use App\Modules\WhatWeSell\Models\WhatWeSell;
use App\Modules\WhatWeSells\Models\WhatWeSells;
use App\Modules\Customer\Models\CustomerFavourites;
use App\Modules\WhatWeSells\Models\WhatWeSellHighlight;

class ItemRepository
{
    public function __construct()
    {
    }

    public function getFeatureLots()
    {
        $featureLots = collect();

        $featureLots['endingsoon'] = $this->getLotsEndingSoon();
        $featureLots['sold'] = $this->getLots('Sold');

        return $featureLots;
    }

    public function getMarketplaceHighlights()
    {
        $marketplaceHighlights = collect();

        $marketplaceHighlights['newArrival'] = $this->getMarketplaceNewArrivalItems();
        $marketplaceHighlights['clearance'] = $this->getMarketplaceClearanceItems();

        return $marketplaceHighlights;
    }

    public function getWhatWeSellCategoryHighlights($id)
    {
        $categoryHighlights = collect();
        $categoryHighlights['buyNow'] = $this->getWhatWeSellCategoryHighlightBuyNow($id);
        $categoryHighlights['sold'] = $this->getWhatWeSellCategoryHighlightSold($id);

        return $categoryHighlights;
    }

    public function getWhatWeSellCategories()
    {
        $whatWeSells = collect();
        $what_we_sells = WhatWeSells::orderBy('order')->get();

        foreach ($what_we_sells as $item) {
            $whatWeSell = [
                'id' => $item->id,
                'title' => $item->title,
                // 'image' => $item->list_image_file_path,
                'image' => $item->full_path,
                'category_id' => $item->category_id,
            ];

            $whatWeSells->push($whatWeSell);
        }
        return $whatWeSells;
    }

    public function getWhatWeSellCategory($id)
    {
        $whatwesell_data = WhatWeSells::find($id);

        return $whatwesell_data;
    }

    public function getWhatWeSellCategoryHighlightBuyNow($id)
    {
        $favourite_list = [];

        if(Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
            $favourite_list = CustomerFavourites::where('customer_favourites.customer_id', '=', $customer_id)->pluck('item_id')->toArray();
        }

        $whatwesell_data = WhatWeSells::find($id);
        $category_id = $whatwesell_data->category_id;

        $whatwesell_buynow_hightlights = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
                ->where('items.status', '=', Item::_IN_MARKETPLACE_)
                ->where('items.category_id', '=', $category_id)
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
                        and item_lifecycles.type = LOWER(items.lifecycle_status)
                        and item_lifecycles.deleted_at is NULL
                        LIMIT 1)'));
                })
                ->join('categories', function ($join) {
                    $join->on('categories.id', '=', 'items.category_id');
                })
                ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status', 'categories.name as category_name')
                ->get();

        // dd($whatwesell_buynow_hightlights->toSql(), $whatwesell_buynow_hightlights->getBindings());

        $highlights = [];
        if (!$whatwesell_buynow_hightlights->isEmpty()) {
            foreach ($whatwesell_buynow_hightlights as $key => $value) {
                $favourite = '';
                if(in_array($value->id, $favourite_list)) {
                    $favourite = 'yes';
                }else{
                    $favourite = 'no';
                }

                $highlights[] = [
                    'item_id' => $value->id,
                    'photoPath' => $value->full_path,
                    'priceStatus' => 'Buy Now Price',
                    // 'price' => number_format($value->buy_it_now_price, 2),
                    'price' => number_format($value->price, 2),
                    'itemTitle' => $value->title,
                    'favourite' => $favourite,
                    'link' => route('marketplace.marketplace-item-detail', ['item_id' => $value->id]),
                    // 'link' => route('marketplace.list', ['type' => $value->category_id])
                ];
            }
            $highlights = collect($highlights);
        }

        return $highlights;
    }

    public function getWhatWeSellCategoryHighlightSold($id)
    {
        $whatwesell_data = WhatWeSells::find($id);
        $wws_highlights = WhatWeSellHighlight::where('what_we_sell_id',$id)->orderBy('order')->get();


        $highlights = [];
        foreach ($wws_highlights as $key => $highlight) {
            $highlights[] = [
                'photoPath' => $highlight->full_path,
                'price' => $highlight->description,
                'priceStatus' => "SOLD",
                'itemTitle' => $highlight->title,
                'buyerLevel' => "",
            ];
        }


        // if ($whatwesell_data->detail_image_1_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_1_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_1_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_1_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_2_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_2_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_2_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_2_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_3_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_3_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_3_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_3_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_4_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_4_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_4_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_4_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_5_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_5_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_5_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_5_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_6_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_6_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_6_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_6_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_7_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_7_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_7_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_7_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_8_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_8_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_8_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_8_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_9_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_9_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_9_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_9_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_10_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_10_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_10_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_10_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_11_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_11_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_11_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_11_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        // if ($whatwesell_data->detail_image_12_file_path != null) {
        //     $detail_img_array = [];
        //     $detail_img_array['photoPath'] = $whatwesell_data->detail_image_12_file_path;
        //     $detail_img_array['price'] = $whatwesell_data->detail_image_12_caption;
        //     $detail_img_array['priceStatus'] = "SOLD";
        //     $detail_img_array['itemTitle'] = $whatwesell_data->detail_image_12_title;
        //     $detail_img_array['buyerLevel'] = "";

        //     array_push($highlights, $detail_img_array);
        // }

        return $highlights;
    }

    public function getSideItems()
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
                ]
                // ,
                // [
                //     'title' => '26 Everton Road',
                //     'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.',
                //     'link' => 'CASE STUDY',
                //     'type' => 'everton'
                // ]
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

    public function getMarketplaceNewArrivalItems($id=0)
    {
        $favourite_list = [];

        if(Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
            $favourite_list = CustomerFavourites::where('customer_favourites.customer_id', '=', $customer_id)->pluck('item_id')->toArray();
        }

        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
            ->where('items.status', '=', Item::_IN_MARKETPLACE_)
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
                    and item_lifecycles.type = LOWER(items.lifecycle_status)
                    and item_lifecycles.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->select('items.*', 'item_images.file_name', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status')
            ->limit(15)
            ->inRandomOrder()
            ->orderBy('item_lifecycles.entered_date', 'DESC')
            ->get();

        // dd($marketplaceItems->toSql(), $marketplaceItems->getBindings());

        $data = [];
        if (!$marketplaceItems->isEmpty()) {
            foreach ($marketplaceItems as $key => $value) {
                $favourite = '';
                if(in_array($value->id, $favourite_list)) {
                    $favourite = 'yes';
                }else{
                    $favourite = 'no';
                }

                $data[] = [
                    // 'photoPath' => 'ecommerce/images/what-to-buy-now/marketplace-item-1.png',
                    'item_id' => $value->id,
                    'photoPath' => $value->full_path,
                    'itemTitle' => $value->name,
                    'priceStatus' => 'Buy Now Price',
                    'minPrice' => number_format($value->price, 2),
                    'maxPrice' => number_format($value->price, 2),
                    'display_minPrice' => number_format($value->price),
                    'display_maxPrice' => number_format($value->price),
                    'itemCurrency' => 'SGD',
                    'favourite' => $favourite,
                    'item_detail_url' => route('marketplace.marketplace-item-detail', ['item_id' => $value->id])
                ];
            }
            $data = collect($data);
        }

        return $data;
    }

    public function getMarketplaceClearanceItems($id=0)
    {
        $favourite_list = [];

        if(Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
            $favourite_list = CustomerFavourites::where('customer_favourites.customer_id', '=', $customer_id)->pluck('item_id')->toArray();
        }

        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_CLEARANCE_])
            ->where('items.status', '=', Item::_IN_MARKETPLACE_)
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
                    and item_lifecycles.type IN ("'.Item::_CLEARANCE_.'")
                    and item_lifecycles.type = LOWER(items.lifecycle_status)
                    and item_lifecycles.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status', 'item_images.full_path')
            ->limit(15)
            ->inRandomOrder()
            ->orderBy('item_lifecycles.entered_date', 'DESC')
            ->get();


        $data = [];
        if (!$marketplaceItems->isEmpty()) {
            foreach ($marketplaceItems as $key => $value) {
                $favourite = '';
                if(in_array($value->id, $favourite_list)) {
                    $favourite = 'yes';
                }else{
                    $favourite = 'no';
                }

                $data[] = [
                    // 'photoPath' => 'ecommerce/images/what-to-buy-now/marketplace-item-1.png',
                    'item_id' => $value->id,
                    'photoPath' => $value->full_path,
                    'itemTitle' => $value->name,
                    'priceStatus' => 'Buy Now PRICE',
                    'minPrice' => number_format($value->price, 2),
                    'maxPrice' => number_format($value->price, 2),
                    'display_minPrice' => number_format($value->price),
                    'display_maxPrice' => number_format($value->price),
                    'itemCurrency' => 'SGD',
                    'favourite' => $favourite,
                    'favourite' => 0,
                    'item_detail_url' => route('marketplace.marketplace-item-detail', ['item_id' => $value->id])
                ];
            }
            $data = collect($data);
        }

        return $data;
    }

    public function getMarketplaceCollaborationsItems()
    {
    }

    public function getMarketplaceItems($id=0)
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
            ->join('categories', function ($join) {
                $join->on('categories.id', '=', 'items.category_id');
            })
            ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status', 'categories.name as category_name')
            ->orderBy('item_lifecycles.entered_date', 'DESC')
            ->limit(15)
            ->get();

        $data = [];

        foreach ($marketplaceItems as $key => $value) {
            $data[] = [
                // 'photoPath' => 'ecommerce/images/what-to-buy-now/marketplace-item-1.png',
                'item_id' => $value->id,
                'photoPath' => $value->full_path,
                'fullPath' => $value->full_path,
                'itemTitle' => $value->title,
                'priceStatus' => 'FIXED PRICE',
                'minPrice' => number_format($value->price, 2),
                'maxPrice' => number_format($value->price, 2),
                'itemCurrency' => 'SGD',
                'favourite' => 0,
                'category_name' => $value->category_name
            ];
        }

        return $data;
    }

    public function getSideMarketItems()
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

    public function getValuationItems()
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

    public function getHotlotzPick()
    {
        $favourite_list = [];

        if(Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
            $favourite_list = CustomerFavourites::where('customer_favourites.customer_id', '=', $customer_id)->pluck('item_id')->toArray();
        }

        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
            ->where('items.status', '=', Item::_IN_MARKETPLACE_)
            ->where('items.is_highlight', '=', 'Y')
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
                    and item_lifecycles.type = LOWER(items.lifecycle_status)
                    and item_lifecycles.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->select('items.*', 'item_images.file_name', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status')
            ->limit(12)
            ->inRandomOrder()
            ->get();

        // dd($marketplaceItems->toSql(), $marketplaceItems->getBindings());

        $data = [];
        if (!$marketplaceItems->isEmpty()) {
            foreach ($marketplaceItems as $key => $value) {
                $favourite = '';
                if(in_array($value->id, $favourite_list)) {
                    $favourite = 'yes';
                }else{
                    $favourite = 'no';
                }

                $data[] = [
                    'item_id' => $value->id,
                    'photoPath' => $value->full_path,
                    'itemTitle' => $value->title,
                    'priceStatus' => 'Buy Now Price',
                    /* 'minPrice' => number_format($value->price, 2),
                    'maxPrice' => number_format($value->price, 2),
                    'price' => number_format($value->price, 2), */
                    'minPrice' => number_format($value->price, 2),
                    'maxPrice' => number_format($value->price, 2),
                    'price' => $value->price,
                    'itemCurrency' => 'SGD',
                    'favourite' => $favourite,
                    'item_detail_url' => route('marketplace.marketplace-item-detail', ['item_id' => $value->id])
                ];
            }
            $data = collect($data);
        }

        return $data;
    }

    public function getItemsByCategory($id, $offset, $limit, $search_query, $type, $price, $sub_category)
    {
        // $category_data = Category::where('name', '=', $category)->first();

        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
        ->where('items.status', '=', Item::_IN_MARKETPLACE_)
        ->where('items.category_id', '=', $id)
        ->leftJoin('item_images', function ($join) {
            $join->on('item_images.id', '=', DB::raw('
                (SELECT item_images.id FROM item_images
                WHERE item_images.item_id = items.id
                and item_images.deleted_at is NULL
                LIMIT 1)'));
        })
        ->leftJoin('item_lifecycles', function ($join) use ($price)  {
            if ($price != '') {
                $join->on('item_lifecycles.id', '=', DB::raw('
                (SELECT item_lifecycles.id FROM item_lifecycles
                WHERE item_lifecycles.item_id = items.id
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL and item_lifecycles.price = '.$price.'
                LIMIT 1)'));
            } else {
                $join->on('item_lifecycles.id', '=', DB::raw('
                (SELECT item_lifecycles.id FROM item_lifecycles
                WHERE item_lifecycles.item_id = items.id
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL
                LIMIT 1)'));
            }
        })
        ->join('categories', function ($join) {
            $join->on('categories.id', '=', 'items.category_id');
        })

        ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status', 'categories.name as category_name');
        // ->take($limit)->skip($offset)
        // ->get();
        // dd($marketplaceItems->toSql(), $marketplaceItems->getBindings());
        if (isset($search_query)) {
            $search_query = explode(" ", $search_query);
            foreach ($search_query as $eachQueryString) {
                $marketplaceItems->where('items.name', 'LIKE', '%'.$eachQueryString .'%');
            }
        }

        if (isset($type)) {
            $marketplaceItems->where('items.status', '=', $type);
        }

        if (isset($sub_category)) {
            if ($sub_category != '') {
                $marketplaceItems->where('items.sub_category', '=', $sub_category);
            }
        }

        return $marketplaceItems;
    }

    public function getItemsNewArrival($offset, $limit, $search_query, $type, $price, $sub_category)
    {
        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
        ->where('items.status', '=', Item::_IN_MARKETPLACE_)
        ->leftJoin('item_images', function ($join) {
            $join->on('item_images.id', '=', DB::raw('
                (SELECT item_images.id FROM item_images
                WHERE item_images.item_id = items.id
                and item_images.deleted_at is NULL
                LIMIT 1)'));
        })
        ->leftJoin('item_lifecycles', function ($join) use ($price)  {
            if ($price != '') {
                $join->on('item_lifecycles.id', '=', DB::raw('
                (SELECT item_lifecycles.id FROM item_lifecycles
                WHERE item_lifecycles.item_id = items.id
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL and item_lifecycles.price = '.$price.'
                LIMIT 1)'));
            } else {
                $join->on('item_lifecycles.id', '=', DB::raw('
                (SELECT item_lifecycles.id FROM item_lifecycles
                WHERE item_lifecycles.item_id = items.id
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL
                LIMIT 1)'));
            }
        })
        ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status');

        // ->take($limit)->skip($offset)
        // ->get();
        if (isset($search_query)) {
            $search_query = explode(" ", $search_query);
            foreach ($search_query as $eachQueryString) {
                $marketplaceItems->where('items.name', 'LIKE', '%'.$eachQueryString .'%');
            }
        }

        if (isset($type)) {
            $marketplaceItems->where('items.status', '=', $type);
        }

        if (isset($sub_category)) {
            if ($sub_category != '') {
                $marketplaceItems->where('items.sub_category', '=', $sub_category);
            }
        }

        return $marketplaceItems->orderBy('item_lifecycles.entered_date', 'DESC');
    }

    public function getItemsNewArrivalTotalCount()
    {
        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
        ->where('items.status', '=', Item::_IN_MARKETPLACE_)
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
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL
                LIMIT 1)'));
        })
        ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status')
        ->orderBy('item_lifecycles.entered_date', 'DESC')
        // ->limit(15)
        ->count();

        return $marketplaceItems;
    }

    public function getItemsClearance($offset, $limit, $search_query, $type, $price, $sub_category)
    {
        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_CLEARANCE_])
        ->where('items.status', '=', Item::_IN_MARKETPLACE_)
        ->leftJoin('item_images', function ($join) {
            $join->on('item_images.id', '=', DB::raw('
                (SELECT item_images.id FROM item_images
                WHERE item_images.item_id = items.id
                and item_images.deleted_at is NULL
                LIMIT 1)'));
        })
        ->leftJoin('item_lifecycles', function ($join) use ($price)  {
            if ($price != '') {
                $join->on('item_lifecycles.id', '=', DB::raw('
                (SELECT item_lifecycles.id FROM item_lifecycles
                WHERE item_lifecycles.item_id = items.id
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL and item_lifecycles.price = '.$price.'
                LIMIT 1)'));
            } else {
                $join->on('item_lifecycles.id', '=', DB::raw('
                (SELECT item_lifecycles.id FROM item_lifecycles
                WHERE item_lifecycles.item_id = items.id
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL
                LIMIT 1)'));
            }
        })
        ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status');

        // ->take($limit)->skip($offset)
        // ->get();
        if (isset($search_query)) {
            $search_query = explode(" ", $search_query);
            foreach ($search_query as $eachQueryString) {
                $marketplaceItems->where('items.name', 'LIKE', '%'.$eachQueryString .'%');
            }
        }

        if (isset($type)) {
            $marketplaceItems->where('items.status', '=', $type);
        }

        if (isset($sub_category)) {
            if ($sub_category != '') {
                $marketplaceItems->where('items.sub_category', '=', $sub_category);
            }
        }

        return $marketplaceItems->orderBy('item_lifecycles.entered_date', 'DESC');
    }

    public function getItemsClearanceTotalCount()
    {
        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_CLEARANCE_])
        ->where('items.status', '=', Item::_IN_MARKETPLACE_)
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
                and item_lifecycles.type IN ("'.Item::_CLEARANCE_.'")
                and item_lifecycles.type = LOWER(items.lifecycle_status)
                and item_lifecycles.deleted_at is NULL
                LIMIT 1)'));
        })
        ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status')
        ->orderBy('item_lifecycles.entered_date', 'DESC')
        ->count();

        return $marketplaceItems;
    }

    public function getItemDetail($id)
    {
        $marketplaceItems = Item::where('items.id', '=', $id)
            ->join('item_images', function ($join) {
                $join->on('item_images.id', '=', DB::raw('
                    (SELECT item_images.id FROM item_images
                    WHERE item_images.item_id = items.id
                    and item_images.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->join('item_lifecycles', function ($join) {
                $join->on('item_lifecycles.id', '=', DB::raw('
                    (SELECT item_lifecycles.id FROM item_lifecycles
                    WHERE item_lifecycles.item_id = items.id
                    and item_lifecycles.type = LOWER(items.lifecycle_status)
                    and item_lifecycles.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->join('categories', function ($join) {
                $join->on('categories.id', '=', 'items.category_id');
            })
            ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.status as item_lifecycle_status', 'categories.name as category_name')
            ->first();

        $result = [

        ];

        return $marketplaceItems;
    }

    public function getItemImages($id)
    {
        $images = ItemImage::where('item_id', '=', $id)->get();

        $data = [];
        if (!$images->isEmpty()) {
            foreach ($images as $image) {
                $data[] = [
                    'photoPath' => $image->full_path,
                    "status" => "active"
                ];
            }
        }

        return $data;
    }

    public function getItemVideos($id)
    {
        $videos = ItemVideo::where('item_id', '=', $id)->get();

        $data = [];
        if (!$videos->isEmpty()) {
            foreach ($videos as $video) {
                $data[] = [
                    'path' => $video->full_path,
                    'thumbnail' => ''
                ];
            }
        }

        return $data;
    }

    private function getLots($status)
    {
        $past_thirty_date = date('Y-m-d', strtotime('-30 day'));

        $lots = AuctionItem::whereNull('auction_items.deleted_at')
            ->whereIn('auction_items.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
            ->whereNotNull('auction_items.lot_id')
            ->whereDate('auction_items.sold_date', '>=', $past_thirty_date)
            ->whereDate('auction_items.sold_date', '<=', date('Y-m-d'))
            ->join('items', 'items.id', 'auction_items.item_id')
            ->whereNull('items.deleted_at')
            ->join('auctions', 'auctions.id', 'auction_items.auction_id')
            ->whereNull('auctions.deleted_at')
            ->leftJoin('item_images', function ($join) {
                $join->on('item_images.id', '=', DB::raw('
                    (SELECT item_images.id FROM item_images
                    WHERE item_images.item_id = items.id
                    and item_images.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->leftJoin('categories', 'categories.id', 'items.category_id')
            ->whereNull('categories.deleted_at')
            ->select(
                'items.name', 'items.item_number', 'items.low_estimate', 'items.high_estimate', 'items.sold_price', 'items.status as item_status',
                'item_images.file_name', 'item_images.full_path', 'categories.name as category',
                'auctions.timed_first_lot_ends', 'auctions.sr_reference',
                'auction_items.auction_id as my_auction_id', 'auction_items.lot_id', 'auction_items.status as auction_status'
            )
            ->inRandomOrder()
            ->orderBy('auction_items.sold_price','desc')
            ->get();
        // dd($lots->toSql(), $lots->getBindings());

        $data = [];
        $minPrice = 0;
        $maxPrice = 0;
        if (!$lots->isEmpty()) {
            foreach ($lots as $key => $value) {
                $label = "";
                $price = 0;
                if ( in_array($value->auction_status, [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_]) ) {
                    $label = 'SOLD';
                    $price = $value->sold_price;
                } else {
                    $label = 'ESTIMATE';
                    $minPrice = $value->low_estimate;
                    $maxPrice = $value->high_estimate;
                }
                $data[] = [
                    "image" => $value->full_path,
                    "title" => $value->name,
                    "slogan" => "Lot ".$value->item_number." | ".$value->category,
                    "time" => "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F'),
                    "label" => $label,
                    "price" => $price,
                    "minPrice" => $minPrice,
                    "maxPrice" => $maxPrice,
                    "link" => 'https://'. config('thesaleroom.atg_tenant_id') . '/auctions/7613/'.$value->sr_reference.'/lot-details/'.$value->lot_id
                ];
            }
            $data = collect($data);
        }
        return $data;
    }

    private function getLotsEndingSoon()
    {
        $lots = DB::table('auction_items')
            ->whereNull('auction_items.deleted_at')
            ->where('auctions.is_closed', '!=', 'Y')
            ->leftJoin('items', 'items.id', 'auction_items.item_id')
            ->whereNull('items.deleted_at')
            ->leftJoin('auctions', 'auctions.id', 'auction_items.auction_id')
            ->whereNull('auctions.deleted_at')
            ->whereNotNull('auction_items.lot_id')
            ->where('auctions.is_published', 'Y')
            ->where('auction_items.auction_id', '!=', 'f27b116b-30d8-4fe4-ae96-3bc50845ee3e')
            // ->where('auctions.timed_start', '<=', date('Y-m-d H:i:s'))
            // ->where('auctions.timed_first_lot_ends', '>', date('Y-m-d H:i:s'))
            ->leftJoin('item_images', function ($join) {
                $join->on('item_images.id', '=', DB::raw('
                    (SELECT item_images.id FROM item_images
                    WHERE item_images.item_id = items.id
                    and item_images.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->leftJoin('categories', 'categories.id', 'items.category_id')
            ->whereNull('categories.deleted_at')
            ->select(
                'items.name', 'items.item_number', 'items.low_estimate', 'items.high_estimate', 'items.status',
                'item_images.file_name', 'item_images.full_path', 'categories.name as category',
                'auctions.timed_first_lot_ends', 'auctions.sr_reference',
                'auction_items.auction_id as my_auction_id', 'auction_items.lot_id', 'auction_items.lot_number'
            )
            ->inRandomOrder()
            ->get();

        $data = [];
        if (!$lots->isEmpty()) {
            foreach ($lots as $key => $value) {
                $label = 'ESTIMATE';
                $price = "$".$value->low_estimate." - $".$value->high_estimate." SGD";
                $lot_number = $value->lot_number ?? 'N/A';
                $data[] = [
                    "image" => $value->full_path,
                    "title" => $value->name,
                    "slogan" => "Lot ". $lot_number . " | ".$value->category,
                    "time" => "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F'),
                    "label" => $label,
                    "price" => $price,
                    "minPrice" => $value->low_estimate,
                    "maxPrice" => $value->high_estimate,
                    "link" => 'https://'. config('thesaleroom.atg_tenant_id') . '/auctions/7613/'.$value->sr_reference.'/lot-details/'.$value->lot_id
                ];
            }
            $data = collect($data);
        }
        return $data;
    }

    public function getCollaborationItems()
    {
        $category_id = 13; //for collaboration
        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
                ->where('items.status', '=', Item::_IN_MARKETPLACE_)
                ->where('items.category_id', '=', $category_id)
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
                        and item_lifecycles.type = LOWER(items.lifecycle_status)
                        and item_lifecycles.deleted_at is NULL
                        LIMIT 1)'));
                })
                ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status')
                ->get();

        return $marketplaceItems;
    }

    public function getAllMarketplaceItems()
    {
        $marketplaceItems = Item::where('items.status', '=', Item::_IN_MARKETPLACE_)
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
                        and item_lifecycles.type = LOWER(items.lifecycle_status)
                        and item_lifecycles.deleted_at is NULL
                        LIMIT 1)'));
                })
                ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status');

        return $marketplaceItems;
    }

    public function getComingSoonLots()
    {
        $auctions = Auction::where('timed_start', '>', date('Y-m-d H:i:s'))
            ->where('publish_to_frontend', 'Y')
            ->select('auctions.*')
            ->orderBy('timed_start', 'ASC')
            ->get();

        $data = [];
        if ($auctions) {
            foreach ($auctions as $key => $value) {
                $slug = $value->coming_auction_url;
                if ($value->coming_auction_url == null) {
                    $slug = 404;
                }
                $auction_type = '';
                if ($value->type == 'Timed') {
                    $auction_type = 'TIMED AUCTION';
                } else {
                    $auction_type = 'Lived AUCTION';
                }
                $data[] = [
                    'auction_id' => $value->id,
                    "photoPath" => $value->full_path,
                    "status" => $auction_type,
                    "title" => $value->title,
                    // "slogon" => "Coming in  ".date_format(date_create($value->timed_first_lot_ends), 'F'),
                    "slogon" => "Coming in  ".date_format(date_create($value->timed_start), 'F'),
                    "link" => "https://" . config('thesaleroom.atg_tenant_id') . "/$value->id",
                    'slug' => $slug
                ];
            }
        }

        return $data;
    }

    public function getSoldItemsForCheckout($item_arr)
    {
        $sold_items = Item::whereIn('items.status', ['Sold', 'Paid','Withdrawn'])
                ->whereIn('items.id', $item_arr)
                ->get();
        // dd($sold_items->toSql(), $sold_items->getBindings());
        return $sold_items;
    }           

    public function getNotInMarketplaceItemsForCheckout($item_arr)
    {
        $items = Item::whereIn('items.id', $item_arr)
                ->where('items.status','!=',Item::_IN_MARKETPLACE_)
                ->get();
        return $items;
    }

    public function getItemTotalCountByCategory($id)
    {
        $marketplaceItems = Item::whereIn('lifecycle_status', [Item::_MARKETPLACE_])
            ->where('items.status', '=', Item::_IN_MARKETPLACE_)
            ->where('items.category_id', '=', $id)
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
                    and item_lifecycles.type = LOWER(items.lifecycle_status)
                    and item_lifecycles.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->join('categories', function ($join) {
                $join->on('categories.id', '=', 'items.category_id');
            })
            ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status', 'categories.name as category_name')
            ->count();

        return $marketplaceItems;
    }
}
