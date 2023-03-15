<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Customer\Models\CustomerFavourites;

class FavouritesRepository
{
    public function __construct()
    {
    }

    public function getFavouriteItem($customer_id, $item_id)
    {
        $data = CustomerFavourites::where('customer_id', '=', $customer_id)->where('item_id', '=', $item_id)->count();

        return $data;
    }

    public function makeUnFavouriteItem($customer_id, $item_id)
    {
        $result = CustomerFavourites::where('customer_id', '=', $customer_id)->where('item_id', '=', $item_id)->delete();

        return $result;
    }

    public function makeFavouriteItem($payload)
    {
        $result = CustomerFavourites::create($payload);

        return $result;
    }

    public function getMyWatchlist($customer_id, $limit=10, $offset=0)
    {
        $favouriteItems = CustomerFavourites::where('customer_favourites.customer_id', '=', $customer_id)
            ->leftJoin('items', function ($join) {
                $join->on('items.id', '=', DB::raw('
                    (SELECT items.id FROM items
                    WHERE items.id = customer_favourites.item_id
                    LIMIT 1)'));
            })
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
            ->select('items.*', 'item_images.file_name', 'item_images.file_path', 'item_images.full_path', 'item_lifecycles.price', 'item_lifecycles.status as item_lifecycle_status', 'items.id as item_id')
            ->where('items.id', '!=', null)
            ->take($limit)
            ->offset($offset)
            ->get();
        // dd($favouriteItems);
        // dd($favouriteItems->toSql(), $favouriteItems->getBindings());
        $data = [];
        if (!$favouriteItems->isEmpty()) {
            foreach ($favouriteItems as $key => $value) {
                $status = '';
                if ($value->status == Item::_SOLD_ || $value->status == Item::_PAID_ || $value->status == Item::_SETTLED_) {
                    $status = "Sold";
                } elseif ($value->status == Item::_DISPATCHED_ || $value->tag == 'dispatched') {
                    if($value->sold_date != null){
                        $status = "Sold";
                    }
                    if($value->sold_date == null){
                        $status = "Unavailable";
                    }
                } elseif ($value->status == Item::_IN_MARKETPLACE_) {
                    $status = "Marketplace";
                } elseif ($value->status == Item::_IN_AUCTION_) {
                    $status = "Auction";
                } elseif ($value->status != Item::_SOLD_ || $value->status != Item::_PAID_ || $value->status != Item::_SETTLED_ || $value->status != Item::_IN_MARKETPLACE_ || $value->status != Item::_IN_AUCTION_ || ($value->status != Item::_DISPATCHED_ || $value->tag != 'dispatched')) {
                    $status = "Unavailable";
                }
                $data[] = [
                    'item_id' => $value->item_id,
                    'photoPath' => $value->full_path,
                    'status' => $status,
                    'itemName' => $value->name,
                    'brand' => $value->brand,
                    'category_id' => $value->category_id,
                    'fee_type' => $value->fee_type,
                    'price' => $value->price,
                    'weight' => $value->weight,
                    'provenance' => $value->provenance,
                    'condition' => $value->condition,
                    'currency' => $value->currency,
                ];
            }
            $data = collect($data);
        }
        // dd($data);
        return $data;
    }

    public function getFavoritelist($customer_id)
    {
        $favouriteItems = CustomerFavourites::where('customer_favourites.customer_id', '=', $customer_id)->pluck('item_id')->toArray();

        return $favouriteItems;
    }
}
