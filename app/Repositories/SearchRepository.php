<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Category\Models\Category;
use App\Modules\Item\Models\ItemLifecycle;

class SearchRepository
{
    public function __construct()
    {
    }

    public function getSearchDataOld($search_value)
    {
        $query = Item::whereIn('items.status', [Item::_IN_MARKETPLACE_, Item::_IN_AUCTION_])->select('items.*');

        if (isset($search_value)) {
            $search_value = explode(" ", $search_value);
            foreach ($search_value as $eachQueryString) {
                $query->where('items.name', 'LIKE', '%'.$eachQueryString .'%');
            }
        }
        $searchedMarketplaceItems = $query
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
            ->leftJoin('auction_items', 'auction_items.item_id', 'items.id')
            ->join('auctions', 'auctions.id', 'auction_items.auction_id')
            ->whereNull('auctions.deleted_at')
            ->whereNotNull('auction_items.lot_id')
            ->where('auctions.is_published', 'Y')
            ->leftJoin('categories', 'categories.id', 'items.category_id')
            ->whereNull('categories.deleted_at')
            ->select('items.*', 'item_images.file_name', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status', 'items.name as item_title', 'auctions.title as auction_title', 'auctions.status as auction_status', 'categories.name as category', 'auctions.sr_reference as auction_sr_reference', 'auction_items.lot_id as auction_lot_id', 'items.id as myitem_id', 'auctions.timed_first_lot_ends')
            ->get();
        $data = [];
        if (!$searchedMarketplaceItems->isEmpty()) {
            foreach ($searchedMarketplaceItems as $key => $value) {
                $itemTitle = '';
                $priceLabel = "";
                $price = "";
                $slogan = "";
                $link = "";
                $time = "";
                if ($value->status == "In Marketplace") {
                    $itemTitle = $value->item_title;
                    $priceLabel = 'Buy Now Price';
                    $price = '$'.number_format($value->price, 2);
                    $link = route('marketplace.marketplace-item-detail', ['item_id' => $value->myitem_id]);
                } elseif ($value->status == Item::_IN_AUCTION_) {
                    if ($value->auction_status == 'Sold') {
                        $priceLabel = 'SOLD';
                        $price = "$".$value->sold_price;
                    } else {
                        $priceLabel = 'Estimate';
                        $price = "$".$value->low_estimate." - $".$value->high_estimate;
                    }

                    $time = "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F');
                    $itemTitle = $value->item_title;
                    $slogan = "Lot ".$value->item_number." | ".$value->category;
                    $link = 'https://'. config('thesaleroom.atg_tenant_id') . '/auctions/7613/'.$value->auction_sr_reference.'/lot-details/'.$value->auction_lot_id;
                }
                $data[] = [
                    'item_id' => $value->id,
                    'photoPath' => $value->full_path,
                    'itemTitle' => $itemTitle,
                    'itemCurrency' => 'SGD',
                    'favourite' => 0,
                    'priceLabel' => $priceLabel,
                    'price' => $price,
                    'itemCurrency' => ' SGD',
                    'status' => $value->status,
                    'slogan' => $slogan,
                    'time' => $time,
                    'link' => $link
                ];
            }
            $data = collect($data);
        }
        return $data;
    }

    public function getSearchDataAll($type, $category, $sub_category, $price, $search_query)
    {
        $query = Item::whereIn('items.status', [Item::_IN_MARKETPLACE_, Item::_IN_AUCTION_])->select('items.*');

        if (isset($search_query)) {
            $search_query = explode(" ", $search_query);
            foreach ($search_query as $eachQueryString) {
                $query->where('items.name', 'LIKE', '%'.$eachQueryString .'%');
            }
        }

        if (isset($type)) {
            $query->where('items.status', '=', $type);
        }

        if (isset($category)) {
            if ($category != 0 && $category != null) {
                $query->where('items.category_id', '=', $category);
            }
        }

        if (isset($sub_category)) {
            if ($sub_category != '') {
                $query->where('items.sub_category', '=', $sub_category);
            }
        }

        $searchedMarketplaceItems = $query
             ->leftJoin('item_images', function ($join) {
                 $join->on('item_images.id', '=', DB::raw('
                    (SELECT item_images.id FROM item_images
                    WHERE item_images.item_id = items.id
                    and item_images.deleted_at is NULL
                    LIMIT 1)'));
             })
            ->leftJoin('item_lifecycles', function ($join) use ($price) {
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
            ->leftJoin('auction_items', 'auction_items.item_id', 'items.id')
            ->join('auctions', 'auctions.id', 'auction_items.auction_id')
            ->whereNull('auctions.deleted_at')
            ->whereNotNull('auction_items.lot_id')
            ->where('auctions.is_published', 'Y')
            ->leftJoin('categories', 'categories.id', 'items.category_id')
            ->whereNull('categories.deleted_at')
            ->select('items.*', 'item_images.file_name', 'item_images.full_path', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.status as item_lifecycle_status', 'items.name as item_title', 'auctions.title as auction_title', 'auctions.status as auction_status', 'categories.name as category', 'auctions.sr_reference as auction_sr_reference', 'auction_items.lot_id as auction_lot_id', 'items.id as myitem_id', 'auctions.timed_first_lot_ends')
            // ->limit(15)
            ->get();
        $data = [];
        if (!$searchedMarketplaceItems->isEmpty()) {
            foreach ($searchedMarketplaceItems as $key => $value) {
                $itemTitle = '';
                $priceLabel = "";
                $price = "";
                $slogan = "";
                $link = "";
                $time = "";
                if ($value->status == "In Marketplace") {
                    $itemTitle = $value->item_title;
                    $priceLabel = 'Buy Now Price';
                    $price = '$'.number_format($value->price, 2);
                    $link = route('marketplace.marketplace-item-detail', ['item_id' => $value->myitem_id]);
                } elseif ($value->status == Item::_IN_AUCTION_) {
                    if ($value->auction_status == 'Sold') {
                        $priceLabel = 'SOLD';
                        $price = "$".$value->sold_price;
                    } else {
                        $priceLabel = 'Estimate';
                        $price = "$".$value->low_estimate." - $".$value->high_estimate;
                    }
                    $time = "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F');
                    $itemTitle = $value->auction_title;
                    $slogan = "Lot ".$value->item_number." | ".$value->category;
                    $link = 'https://'. config('thesaleroom.atg_tenant_id') . '/auctions/7613/'.$value->auction_sr_reference.'/lot-details/'.$value->auction_lot_id;
                }
                $data[] = [
                    'item_id' => $value->id,
                    'photoPath' => $value->full_path,
                    'itemTitle' => $itemTitle,
                    'itemCurrency' => 'SGD',
                    'favourite' => 0,
                    'priceLabel' => $priceLabel,
                    'price' => $price,
                    'itemCurrency' => ' SGD',
                    'status' => $value->status,
                    'slogan' => $slogan,
                    'time' => $time,
                    'link' => $link
                ];
            }
            $data = collect($data);
        }
        return $data;
    }

    public function getSearchData($search_value, $type = null, $category = null, $sub_category = null, $price = null)
    {
        $query = Item::whereIn('items.status', [Item::_IN_MARKETPLACE_, Item::_IN_AUCTION_]);

        if (isset($search_value)) {
            $search_value = explode(" ", $search_value);
            foreach ($search_value as $eachQueryString) {
                $query->where('items.name', 'LIKE', '%'.$eachQueryString .'%');
            }
        }

        if (isset($type)) {
            $query->where('items.status', '=', $type);
        }

        if (isset($category)) {
            if ($category != 0 && $category != null) {
                $query->where('items.category_id', '=', $category);
            }
        }

        if (isset($sub_category)) {
            if ($sub_category != '') {
                $query->where('items.sub_category', '=', $sub_category);
            }
        }

        $searchedItems = $query
            ->join('item_lifecycles', function ($join) use ($price) {
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
            ->leftJoin('categories', 'categories.id', 'items.category_id')
            ->whereNull('categories.deleted_at')
            ->select('items.id', 'items.name', 'items.status', 'items.lifecycle_status', 'items.item_number', 'items.low_estimate', 'items.high_estimate', 'items.sold_price'
                , 'categories.name as category'
                , 'item_lifecycles.price'
            )
            ->get();
        // dd($searchedItems->toArray());

        $data = [];
        if (!$searchedItems->isEmpty()) {
            foreach ($searchedItems as $key => $value) {

                $itemTitle = '';
                $priceLabel = "";
                $price = "";
                $slogan = "";
                $link = "";
                $time = "";

                $item_image = ItemImage::where('item_id',$value->id)->first();
                $itemlifecycle = ItemLifecycle::where('item_id',$value->id)->where('type',strtolower($value->lifecycle_status))->first();
                if($itemlifecycle && $itemlifecycle != null && isset($itemlifecycle) && !is_null($itemlifecycle) && !empty($itemlifecycle)) {                

                    if ($value->status == "In Marketplace") {
                        $itemTitle = $value->name;
                        $priceLabel = 'Buy Now Price';
                        $price = ($itemlifecycle->price != null)?'$'.number_format($itemlifecycle->price, 2):'';
                        $link = route('marketplace.marketplace-item-detail', ['item_id' => $value->id]);
                    } elseif ($value->status == Item::_IN_AUCTION_) {
                        $priceLabel = 'Estimate';
                        $price = "$".$value->low_estimate." - $".$value->high_estimate;
                        $slogan = "Lot ".$value->item_number." | ".$value->category;

                        $auction = Auction::where('id',$itemlifecycle->reference_id)->where('is_published','Y')->first();
                        if($auction && $auction != null && isset($auction) && !is_null($auction) && !empty($auction)) {
                            $itemTitle = $auction->title;
                            $time = ($auction->timed_first_lot_ends != null)?("Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F')):'';

                            $auctionitem = AuctionItem::where('item_id',$value->id)->where('auction_id',$auction->id)->first();
                            if($auctionitem && $auctionitem != null && isset($auctionitem) && !is_null($auctionitem) && !empty($auctionitem) && $auctionitem->lot_id != null && $auction->sr_reference != null) {
                                $link = 'https://'. config('thesaleroom.atg_tenant_id') . '/auctions/7613/'.$auction->sr_reference.'/lot-details/'.$auctionitem->lot_id;
                            }
                        }
                    }

                    if($itemTitle != ''){
                        $data[] = [
                            'item_id' => $value->id,
                            'photoPath' => $item_image?$item_image->full_path:null,
                            'itemTitle' => $itemTitle,
                            'itemCurrency' => 'SGD',
                            'favourite' => 0,
                            'priceLabel' => $priceLabel,
                            'price' => $price,
                            'itemCurrency' => ' SGD',
                            'status' => $value->status,
                            'slogan' => $slogan,
                            'time' => $time,
                            'link' => $link
                        ];
                    }
                }
            }
            $data = collect($data);
        }
        // dd($data);
        return $data;
    }
}
