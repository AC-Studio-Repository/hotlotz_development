<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;

class AuctionRepository
{
    public function __construct()
    {
    }

    public function getAuctionCatalogues()
    {
        $current_datetime = date('Y-m-d H:i:s');
        $auctions = Auction::where('timed_start', '<', $current_datetime)
                    // ->where('timed_first_lot_ends', '>', $current_datetime) //**Auction link disappearing before the whole auction is closed
                    ->where('publish_to_frontend', 'Y')
                    ->where('is_published', 'Y')
                    ->where('is_closed', '!=', 'Y')
                    // ->leftJoin('item_images', function ($join) {
                    //     $join->on('item_images.id', '=', DB::raw('
                    //         (SELECT item_images.id FROM item_images
                    //         LEFT JOIN items ON items.id = item_images.item_id
                    //         LEFT JOIN auction_items ON auction_items.item_id = items.id
                    //         WHERE auction_items.auction_id = auctions.id
                    //         and item_images.deleted_at is NULL
                    //         and items.deleted_at is NULL
                    //         and auction_items.deleted_at is NULL
                    //         LIMIT 1)'));
                    // })
                    ->select('auctions.*')
                    ->orderBy('timed_start', 'Desc')
                    // ->limit(4)
                    ->get();

        $data = [];
        if (!$auctions->isEmpty()) {
            foreach ($auctions as $key => $value) {
                $sgtEndTime = date_format(date_create($value->timed_first_lot_ends), 'ga');
                $gmtEndTime = gmdate('ga', strtotime($value->timed_first_lot_ends));
                $auction_type = '';
                if ($value->type == 'Timed') {
                    $auction_type = 'TIMED AUCTION';
                } else {
                    $auction_type = 'Lived AUCTION';
                }

                $data[] = [
                    'image' => $value->full_path,
                    "status" => $auction_type,
                    "title" => $value->title,
                    "slogan" => $value->important_information,
                    "sr_auction_id" => $value->sr_auction_id,
                    // "sellingTime" => "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends),'l d F')." from ".$sgtEndTime." (SGT) / ".$gmtEndTime." (GMT)",
                    "sellingDate" => "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F'),
                    "sellingTime" => "From ".$sgtEndTime." (SGT) / ".$gmtEndTime." (GMT)",
                    "link" => 'https://'. config('thesaleroom.atg_tenant_id') . '/auctions/9999/'.$value->sr_reference
                ];
            }
            $data = collect($data);
        }
        return $data;
    }

    public function getPastAuctionCount()
    {
        $past_auctions_count = 0;
        $past_auctions_count = Auction::where('is_closed', 'Y')->count();
        return $past_auctions_count;
    }

    public function getPastAuctionCatalogues($offset, $limit)
    {
        $auctions = Auction::where('is_closed', 'Y')
                ->select('auctions.*')
                ->orderBy('timed_start', 'DESC')
                ->take($limit)->skip($offset)
                ->get();

        $data = [];
        if (!$auctions->isEmpty()) {
            foreach ($auctions as $key => $value) {
                $sgtEndTime = date_format(date_create($value->timed_first_lot_ends), 'ga');
                $gmtEndTime = gmdate('ga', strtotime($value->timed_first_lot_ends));
                $auction_type = '';
                if ($value->type == 'Timed') {
                    $auction_type = 'TIMED AUCTION';
                } else {
                    $auction_type = 'Lived AUCTION';
                }

                $data[] = [
                    'photoPath' => $value->full_path,
                    "status" => $auction_type,
                    "title" => $value->title,
                    "slogon" => $value->important_information,
                    "sr_auction_id" => $value->sr_auction_id,
                    "sellingTime" => "Auction Closed",
                    "sr_reference" => "https://" . config('thesaleroom.atg_tenant_id'). "/past-auctions/" .$value->sr_reference
                ];
            }

            $data = collect($data);
        }
        // dd($data);
        return $data;
    }

    public function getNextAuction()
    {
        $current_datetime = date('Y-m-d H:i:s');
        $nextAuction = Auction::where('is_closed','!=','Y')
            ->where('timed_first_lot_ends', '>', $current_datetime)
            ->where('publish_to_frontend', 'Y')
            ->select('auctions.*')
            ->orderBy('timed_first_lot_ends', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->first();

        $data = [];
        if ($nextAuction) {
            $sgtEndTime = date_format(date_create($nextAuction->timed_first_lot_ends), 'ga');
            $gmtEndTime = gmdate('ga', strtotime($nextAuction->timed_first_lot_ends));
            $auction_type = '';
            if ($nextAuction->type == 'Timed') {
                $auction_type = 'TIMED AUCTION';
            } elseif ($nextAuction->type == 'Live') {
                $auction_type = 'LIVED AUCTION';
            }

            $link = route('auctions.forth-coming', $nextAuction->coming_auction_url);
            if ($nextAuction->coming_auction_url == null) {
                $link = '';
            }
            if ($nextAuction->is_published == 'Y') {
                $link = "https://" . config('thesaleroom.atg_tenant_id'). "/auctions/9999/".$nextAuction->sr_reference;
            }

            $data = [
                'image' => $nextAuction->full_path,
                'status' => $auction_type,
                'title' => $nextAuction->title,
                'slogan' => $nextAuction->important_information,
                // 'time' => strtoupper("Bidding ends on ".date_format(date_create($nextAuction->timed_first_lot_ends),'l d F')." from ".$sgtEndTime." (SGT) / ".$gmtEndTime." (GMT)"),
                "title_status" => '<span class="text_red">'.$auction_type.'</span> | '.$nextAuction->title.'</h4>',
                'link' => $link,
                // "link" => "https://" . config('thesaleroom.atg_tenant_id'). "/auctions/9999/" .$nextAuction->sr_reference,

                "date" => strtoupper("Bidding ends on ".date_format(date_create($nextAuction->timed_first_lot_ends), 'l d F')),
                "time" => strtoupper("from ".$sgtEndTime." (SGT) / ".$gmtEndTime." (GMT)"),
            ];
        // $data = collect($data);
        } else {
            $data = [
                "image" => '',
                "status" => "",
                "title" => "",
                "slogan" => "",
                "time" => "",
                "title_status" => "",
                "link" => "",
                "date" => ""
            ];
        }
        // return $auction;
        return $data;
    }

    public function getBidOnFeatureLots($status = null)
    {
        $lots = DB::table('auction_items')
            ->whereNull('auction_items.deleted_at')
            ->leftJoin('items', 'items.id', 'auction_items.item_id')
            ->where('items.status', '=', $status)
            ->whereNull('items.deleted_at')
            ->leftJoin('auctions', 'auctions.id', 'auction_items.auction_id')
            ->whereNull('auctions.deleted_at')
            ->where('auctions.is_published', 'Y')
            ->leftJoin('item_images', function ($join) {
                $join->on('item_images.id', '=', DB::raw('
                    (SELECT item_images.id FROM item_images
                    WHERE item_images.item_id = items.id
                    and item_images.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->leftJoin('categories', 'categories.id', 'items.category_id')
            ->whereNull('categories.deleted_at')
            ->where('auctions.timed_start', '<=', date('Y-m-d H:i:s'))
            ->where('auctions.timed_first_lot_ends', '>', date('Y-m-d H:i:s'))
            ->select('items.*', 'item_images.file_name', 'item_images.full_path', 'auction_items.auction_id as my_auction_id', 'auctions.timed_first_lot_ends', 'categories.name as category')
            ->get();

        $data = [];
        if (!$lots->isEmpty()) {
            foreach ($lots as $key => $value) {
                $data[] = [
                    "photoPath" => $value->full_path,
                    "title" => $value->title,
                    "slogon" => "Lot ".$value->item_number." | ".$value->category,
                    "sellingTime" => "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F'),
                    "priceLabel" => "ESTIMATE",
                    "price" => "$".$value->low_estimate." - $".$value->high_estimate." SGD"
                ];
            }
        }

        return $data;
    }

    public function getForthComingAuctions()
    {
        $current_datetime = date('Y-m-d H:i:s');
        $auctions = Auction::where('timed_start', '<', $current_datetime)
            // ->where('timed_first_lot_ends', '>', $current_datetime) //**Auction link disappearing before the whole auction is closed
            ->where('publish_to_frontend', 'Y')
            ->where('is_published', '=', 'Y')
            ->where('auctions.is_closed', '!=', 'Y')
            ->select('auctions.*')
            ->orderBy('timed_start', 'ASC')
            ->get();

        $data = [];
        if ($auctions) {
            foreach ($auctions as $key => $value) {
                $sgtEndTime = date_format(date_create($value->timed_first_lot_ends), 'ga');
                $gmtEndTime = gmdate('ga', strtotime($value->timed_first_lot_ends));

                $data[] = [
                    "auction_id" => $value->id,
                    "photoPath" => $value->full_path,
                    "status" => "Timed Auction",
                    "title" => $value->title,
                    "slogon" => $value->important_information,
                    // "sellingTime" => "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends),'l d F')." from ".$sgtEndTime." (SGT) / ".$gmtEndTime." (GMT)",
                    "sellingDate" => "Bidding ends on ".date_format(date_create($value->timed_first_lot_ends), 'l d F'),
                    "sellingTime" => "From ".$sgtEndTime." (SGT) / ".$gmtEndTime." (GMT)",
                    "sr_reference" => "https://" . config('thesaleroom.atg_tenant_id'). "/auctions/9999/" .$value->sr_reference
                ];
            }
        }

        return $data;
    }


    public function getAuctionResultByMonth($year, $month)
    {
        // dd(gettype($month_arr));
        $result = Auction::whereYear('timed_start', '=', $year)
            ->whereMonth('timed_start', '=', $month)
            // ->whereBetween(DB::raw("month(timed_start)"), [$month, $month]) // try to get all month data
            ->where('auctions.is_closed', '=', 'Y')
            ->join('auction_items', function ($join) {
                $join->on('auction_items.auction_id', '=', 'auctions.id')
                ->whereNotNull('auction_items.lot_id')
                ->where('auction_items.status', '=', Item::_SOLD_);
            })
            ->select('auctions.*', 'auction_items.lot_number', 'auction_items.lot_id', 'auction_items.sold_price', 'auction_items.item_id as item_id', 'auctions.id as auction_id')
            ->orderBy('timed_start', 'ASC')
            ->get();
        // dd($result->toSql(), $result->getBindings());
        // dd($result);
        $auction_result = [];
        $lot_data = [];
        if (!$result->isEmpty()) {
            $lot_index = -1;
            foreach ($result as $key => $value) {
                $result = $this->array_walk($lot_data, $value->auction_id, $lot_index);
                if ($result != false) {
                    $lot_index++;
                    $formatted_date = date('M d Y, l', strtotime($value->timed_start));
                    $lot_data[] = [
                        "lot_name" => $value->title,
                        "formatted_date" => $formatted_date,
                        "auction_id" => $value->auction_id,
                    ];
                }
            }
        }

        if (!empty($lot_data)) {
            $index = 0;

            foreach ($lot_data as $key=>$value) {
                $auction_items = $this->getAcutionsResultByAuctionId($value['auction_id']);

                if (!$auction_items->isEmpty()) {
                    foreach ($auction_items as $key => $value) {
                        $auction_result[] = [
                            "lot_number" => $value->lot_number,
                            "hammer_price" => $value->sold_price,
                        ];
                    }
                }
                $lot_data[$index]['item_data'] = $auction_result;
                $index++;
            }
        }
        // dd($lot_data);

        return $lot_data;
    }

    protected function array_walk($arr, $needle, $lot_index)
    {
        if (!empty($arr)) {
            if ($arr[$lot_index]['auction_id'] == $needle) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function getAcutionsResultByAuctionId($auction_id)
    {
        $auction_items = DB::table('auction_items')
            ->where('auction_items.auction_id', '=', $auction_id)
            ->whereNotNull('auction_items.lot_id')
            ->where('auction_items.status', '=', Item::_SOLD_)
            ->select('auction_items.*')
            ->get();

        return $auction_items;
    }

    public function getAuctionResultTotalByMonth($year, $month)
    {
        $result = Auction::whereYear('timed_start', '=', $year)
            ->whereMonth('timed_start', '=', $month)
            // ->whereBetween(DB::raw("month(timed_start)"), [$month, $month]) // try to get all month data
            ->where('auctions.is_closed', '=', 'Y')
            ->join('auction_items', function ($join) {
                $join->on('auction_items.auction_id', '=', 'auctions.id')
                ->whereNotNull('auction_items.lot_id')
                ->where('auction_items.status', '=', Item::_SOLD_);
            })
            ->select('auctions.*', 'auction_items.lot_number', 'auction_items.lot_id', 'auction_items.sold_price', 'auction_items.item_id as item_id', 'auctions.id as auction_id')
            ->orderBy('timed_start', 'ASC')
            ->count();

        return $result;
    }


    public function getAuctionResultByMonthOld($year, $month, $limit, $offset)
    {
        // dd(gettype($month_arr));
        $result = Auction::whereYear('timed_start', '=', $year)
            ->whereMonth('timed_start', '=', $month)
            // ->whereBetween(DB::raw("month(timed_start)"), [$month, $month]) // try to get all month data
            ->where('auctions.is_closed', '=', 'Y')
            ->join('auction_items', function ($join) {
                $join->on('auction_items.auction_id', '=', 'auctions.id')
                ->whereNotNull('auction_items.lot_id')
                ->where('auction_items.status', '=', Item::_SOLD_);
            })
            ->select('auctions.*', 'auction_items.lot_number', 'auction_items.lot_id', 'auction_items.sold_price', 'auction_items.item_id as item_id', 'auctions.id as auction_id')
            ->orderBy('timed_start', 'ASC')
            ->take($limit)->offset($offset)
            ->get();
        // dd($result->toSql(), $result->getBindings());
        // dd($result);
        $auction_result = [];
        $lot_data = [];
        if (!$result->isEmpty()) {
            $lot_index = -1;
            foreach ($result as $key => $value) {
                $result = $this->array_walk($lot_data, $value->auction_id, $lot_index);
                if ($result != false) {
                    $lot_index++;
                    $formatted_date = date('M d Y, l', strtotime($value->timed_start));
                    $lot_data[] = [
                        "lot_name" => $value->title,
                        "formatted_date" => $formatted_date,
                        "auction_id" => $value->auction_id,
                    ];
                }

                $auction_result[] = [
                    "lot_number" => $value->lot_number,
                    "hammer_price" => $value->sold_price,
                ];
            }
        }

        $data = [
            'lot_data' => $lot_data,
            'auction_result' => $auction_result
        ];

        return $data;
    }

    public function getAuctionResults()
    {
        $auctions = Auction::where('is_closed','Y')
                ->whereIn('status',['Submitted','ChecksInProgress','ReadyToInvoice','Invoiced'])
                ->select('id','title','status','type','timed_start','timed_first_lot_ends','sr_reference','sr_auction_id')
                ->orderBy('timed_first_lot_ends','desc')
                ->get();
        // dd($auctions->toArray());

        $results = [];
        $auction_results = [];
        foreach ($auctions as $key => $auction) {
            $end_time = strtotime($auction->timed_first_lot_ends);
            $month_label = date("F Y",$end_time);

            $results[$month_label][] = [
                'auction_id' => $auction->id,
                'title' => $auction->title,
                'link' => "https://" . config('thesaleroom.atg_tenant_id'). "/past-auctions/" .$auction->sr_reference
            ];
            $results = array_slice($results, 0, 12);
        }
        foreach ($results as $key => $data) {
            $auction_results[] = [
                'month_label'=>$key,
                'auctions'=>$data,
            ];
        }

        return $auction_results;
    }

    public function getComingAuctionItems($auction)
    {
        $data = [];

        $auction_items = AuctionItem::where('auction_items.auction_id', $auction->id)
            ->join('items', 'items.id', 'auction_items.item_id')
            ->where('items.recently_consigned', 1)
            ->whereNull('items.deleted_at')
            ->join('item_lifecycles', 'item_lifecycles.item_id', 'items.id')
            ->whereNull('item_lifecycles.deleted_at')
            ->where('item_lifecycles.reference_id', $auction->id)
            ->join('customers', 'customers.id', 'items.customer_id')
            ->whereNull('customers.deleted_at')
            ->select('auction_items.item_id', 'auction_items.lot_id', 'auction_items.lot_number', 'auction_items.status as itemstatus', 'items.name', 'items.buyer_id', 'items.item_number', 'item_lifecycles.price', 'customers.fullname', 'customers.id as seller_id')
            ->orderBy('auction_items.sequence_number')
            ->orderBy('auction_items.lot_number')
            ->get();

        if (count($auction_items)) {
            foreach ($auction_items as $key => $auction_item) {

                $photo = ItemImage::where('item_id', $auction_item->item_id)->select('file_name', 'full_path')->first();

                $data[] = [
                    'item_image' => ($photo) ? $photo->full_path : '',
                    'item_name' => $auction_item->name,
                ];
            }
        }

        return $data;
    }
}
