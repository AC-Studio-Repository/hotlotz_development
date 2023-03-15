<?php

namespace App\Modules\Auction\Http\Repositories;

use Illuminate\Support\Str;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Events\AuctionUpdatedEvent;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemHistory;
use App\Modules\Category\Models\Category;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\ItemFeeStructure;
use App\Modules\Customer\Models\CustomerInvoice;

class AuctionRepository
{
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }
    
    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0)
    {
        return $this->auction
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        if ($withTrash) {
                            return $query->withEagerTrashed($eagerLoad);
                        } else {
                            return $query->with($eagerLoad);
                        }
                    })
                    ->when($paginateCount, function ($query, $role) use ($paginateCount) {
                        return $query->paginate($paginateCount);
                    }, function ($query) {
                        return $query->get();
                    });
    }

    public function show($column, $value, $eagerLoad = [], $withTrash = false, $returnMany = false)
    {
        return $this->auction
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        if ($withTrash) {
                            return $query->withEagerTrashed($eagerLoad);
                        } else {
                            return $query->with($eagerLoad);
                        }
                    })
                    ->where($column, $value)
                    ->when($returnMany, function ($query, $role) {
                        return $query->get();
                    }, function ($query) {
                        return $query->first();
                    });
    }

    public function create($payload)
    {
        return $this->auction->create($payload);
    }

    public function update($id, $payload, $withTrash = false)
    {
        $updated = $this->auction
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);

        event(new AuctionUpdatedEvent($this->auction->find($id)));

        return $updated;
    }

    public function canDestroy($id)
    {
        return $this->auction->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1)
    { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->auction->destroy($id);
        } else {
            return $this->auction->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id)
    {
        return $this->auction->withTrashed()->find($id)->restore();
    }

    public function generateLabel($auction)
    {
        $data = $this->getPreAuctionItems($auction, 'generate_label');
        return $data;
    }

    public function generateCatalogue($auction)
    {
        $data = $this->getPreAuctionItems($auction, 'generate_catalogue');
        return $data;
    }

    public function generateBuyerLabel($auction)
    {
        $auction_items = AuctionItem::where('auction_items.auction_id', $auction->id)
            ->whereNotNull('auction_items.lot_id')
            ->whereIn('auction_items.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_,Item::_DISPATCHED_])
            ->join('items', 'items.id', 'auction_items.item_id')
            ->where('items.status', '!=', Item::_DECLINED_)
            ->whereNull('items.deleted_at')
            ->join('customers', 'customers.id', 'items.buyer_id')
            ->whereNull('customers.deleted_at')
            ->select(
                'auction_items.lot_id',
                'auction_items.lot_number',
                'auction_items.status as itemstatus',
                'items.item_number',
                'items.sold_date',
                'items.buyer_id',
                'items.name as itemName',
                'customers.ref_no',
                'customers.fullname'
            )
            ->orderBy('auction_items.sequence_number')
            ->orderBy('auction_items.lot_number')
            ->get();
        // dd(count($auction_items));

        $data = [];
        if (count($auction_items) > 0) {
            foreach ($auction_items as $key => $auction_item) {
                $data[] = [
                    'title' => $auction->title,
                    'buyer_ref' => $auction_item->ref_no,
                    'buyer_fullname' => $auction_item->fullname,
                    'sale_date' => date_format(date_create($auction->timed_first_lot_ends), 'Y/m/d'),
                    'lot_number' => $auction_item->lot_number,
                    'item_number' => $auction_item->item_number,
                    'item_name' => $auction_item->itemName
                ];
            }
        }

        return $data;
    }

    public function generateSaleReport($auction, $seller_id = null, $status = null, $base64 = false)
    {
        $data = [];
        if ($auction->is_closed == 'Y') {
            $auction_items = AuctionItem::where('auction_items.auction_id', $auction->id)
                ->whereNotNull('auction_items.lot_id')
                ->whereNotNull('auction_items.status')
                ->join('items', 'items.id', 'auction_items.item_id')
                ->when($seller_id != null, function ($query) use ($seller_id) {
                    return $query->where('items.customer_id', $seller_id);
                })
                ->when($status != null, function ($query) use ($status) {
                    if($status == Item::_UNSOLD_){
                        return $query->where('auction_items.status', $status);
                    }
                    if($status == Item::_SOLD_){
                        return $query->whereIn('auction_items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]);
                    }
                })
                ->where('items.status', '!=', Item::_DECLINED_)
                ->whereNull('items.deleted_at')
                ->select(
                    'auction_items.item_id',
                    'auction_items.lot_id',
                    'auction_items.lot_number',
                    'auction_items.status as itemstatus',
                    'auction_items.number_of_bids',
                    'items.customer_id',
                    'items.buyer_id'
                )
                ->orderBy('auction_items.sequence_number')
                ->orderBy('auction_items.lot_number')
                ->get();
            if (count($auction_items) > 0) {

                $lot_bids = [];
                if($auction->lot_bids != null && count($auction->lot_bids) > 0){
                    $lot_bids = $auction->lot_bids;
                }else{
                    $sale_results = Item::getSaleResultByAuctionId($auction->sr_auction_id);
                    if (!isset($sale_results['error'])) {
                        foreach ($sale_results as $key => $value) {
                            $lot_bids[$value['lot_id']] = $value['number_of_bids'];
                        }
                        if(count($lot_bids) > 0){
                            Auction::where('id',$auction->id)->update(['lot_bids'=>$lot_bids]);
                        }
                    }
                }

                foreach ($auction_items as $key => $auction_item) {

                    $photo = ItemImage::where('item_id', $auction_item->item_id)->first();

                    $itemlifecycle = ItemLifecycle::where('item_id', $auction_item->item_id)
                                    ->where('reference_id', $auction->id)
                                    ->where('type', 'auction')
                                    ->first();

                    $starting_bid = '0.00';
                    if ($itemlifecycle != null) {
                        $starting_bid = $itemlifecycle->price;
                    }

                    $item_status = Item::_UNSOLD_;
                    if (in_array($auction_item->itemstatus, [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_,Item::_DISPATCHED_])) {
                        $item_status = Item::_SOLD_;
                    }

                    $buyer = '';
                    $buyer_id = '';
                    if ($auction_item->buyer_id > 0) {
                        $buyer_id = $auction_item->buyer_id;
                        $buyer_info = Customer::find($buyer_id);
                        if ($buyer_info != null) {
                            $buyer = $buyer_info->fullname;
                        }
                    }

                    $seller = '';
                    $seller_id = '';
                    if ($auction_item->customer_id > 0) {
                        $seller_id = $auction_item->customer_id;
                        $seller_info = Customer::find($seller_id);
                        if ($seller_info != null) {
                            $seller = $seller_info->fullname;
                        }
                    }

                    $bidding_history_link = "https://toolbox.globalauctionplatform.com/auction-".$auction->sr_auction_id."/lot-bids?lotID=".$auction_item->lot_id;

                    $number_of_bids = $lot_bids[$auction_item->lot_id] ?? 0;

                    $total = 0.00;
                    $hammar_price = 0.00;
                    $itemDetail = Item::find($auction_item->item_id);
                    if ($itemDetail != null) {
                        if (isset($itemDetail->xeroInvoice)) {
                            $buyerPremiun = $auction->buyers_premium;
                            $total = $itemDetail->final_price + $itemDetail->xeroInvoice->getBuyerPremiun($buyerPremiun);
                            $hammar_price = $itemDetail->final_price;
                        }

                        $opciones_ssl=array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        );
                        $itemFeeStructure = ItemFeeStructure::where('item_id',$auction_item->item_id)->first();
                        $relatedBill = CustomerInvoice::where('invoice_id', $itemDetail->bill_id)->first();
                        if($relatedBill){
                            $bill_number = $relatedBill->invoice_number;
                        }else{
                            $bill_number = null;
                        }
                        $data[] = [
                            'item_id' => $auction_item->item_id,
                            'item_image' => ($photo != null)?$photo->image_path:asset('images/default.jpg'),
                            'item_image_full' => ($photo != null)?$photo->full_path:null,
                            'lot_number' => $auction_item->lot_number,
                            'item_name' => $itemDetail->name,
                            'item_status' => $item_status,
                            'item_number' => $itemDetail->item_number,
                            'buyer_id' => $buyer_id,
                            'buyer' => $buyer,
                            'seller_id' => $seller_id,
                            'seller' => $seller,
                            'starting_bid' => '$ '.number_format($starting_bid).' SGD',
                            'hammar_price' => number_format($hammar_price),
                            'total' => number_format($total),
                            'no_of_bid' => $number_of_bids,
                            'bidding_history_link' => $bidding_history_link,
                            'fixed_cost_sales_fee_cal' => $itemFeeStructure->fixed_cost_sales_fee_cal == 0 ? 0 : $itemFeeStructure->fixed_cost_sales_fee_cal,
                            'sales_commission_cal' => $itemFeeStructure->sales_commission_cal == 0 ? 0 : $itemFeeStructure->sales_commission_cal,
                            'performance_commission_cal' => $itemFeeStructure->performance_commission_cal == 0 ? 0 : $itemFeeStructure->performance_commission_cal,
                            'insurance_fee_cal' => $itemFeeStructure->insurance_fee_cal == 0 ? 0 : $itemFeeStructure->insurance_fee_cal,
                            'listing_fee_cal' => $itemFeeStructure->listing_fee_cal == 0 ? 0 : $itemFeeStructure->listing_fee_cal,
                            'bill_id' => $bill_number
                        ];
                    }
                }
            }
        }

        return $data;
    }

    public function getPreAuctionItems($auction, $type = null)
    {
        $auction_items = AuctionItem::whereNull('auction_items.deleted_at')
                ->where('auction_items.auction_id', $auction->id)
                ->orderBy('auction_items.sequence_number')
                ->orderBy('auction_items.lot_number');

        if($auction->is_closed == 'Y'){
            $auction_items = $auction_items->whereNotNull('auction_items.lot_id')->get();
        }else{
            $auction_items = $auction_items->get();
        }

        // if($type == 'total_count'){
        //     return count($auction_items);
        // }

        $lot_data = [];
        $preauction_items = [];
        $generate_label_items = [];
        $generate_catalogue_items = [];
        $reorder_lots = [];
        $total_starting_bid = 0;
        $total_low_estimate = 0;
        $total_high_estimate = 0;
        $total_lot_count = 0;

        $generate_catalogue_items['auction_info'] = $auction->title.' - Closing on '.date_format(date_create($auction->timed_first_lot_ends), 'jS F Y').' | from '.date_format(date_create($auction->timed_first_lot_ends), 'h:i A');
        $generate_catalogue_items['results'] = [];

        if (count($auction_items) > 0) {
            foreach ($auction_items as $key => $auction_item) {
                $photo = ItemImage::where('item_id', $auction_item->item_id)->first();

                $itemlifecycle = ItemLifecycle::where('item_id', $auction_item->item_id)
                                ->where('reference_id', $auction->id)
                                ->where('type', 'auction')
                                ->first();

                $starting_bid = '0.00';
                if ($itemlifecycle != null) {
                    $starting_bid = $itemlifecycle->price;
                }

                $catalogue_letter = 'C';
                $catalogue_color = 'orange';
                $valuation_letter = '';
                $valuation_color = '';
                $fee_structure_letter = '';
                $fee_structure_color = '';
                $permission_to_sell_letter = '';
                $permission_to_sell_color = '';

                $itemDetail = Item::find($auction_item->item_id);

                $is_exist_in_first_auction = 'no';
                if ($itemlifecycle != null && $itemDetail != null && in_array($itemDetail->status, [Item::_SWU_, Item::_PENDING_, Item::_PENDING_IN_AUCTION_, Item::_IN_AUCTION_])) {
                    $checkItemlifecycle = ItemLifecycle::where('item_id', $auction_item->item_id)
                                ->where('reference_id', '!=', $auction->id)
                                ->where('id', '<', $itemlifecycle->id)
                                ->where('type', 'auction')
                                ->first();

                    if ($checkItemlifecycle != null) {
                        $first_auction = Auction::find($checkItemlifecycle->reference_id);
                        if ($first_auction != null && $first_auction->is_closed != 'Y') {
                            $is_exist_in_first_auction = 'yes';
                        }
                    }
                }

                $is_sold_in_first_auction = 'no';
                if ($itemDetail != null && (in_array($itemDetail->status,[Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($itemDetail->status == Item::_DISPATCHED_ || $itemDetail->tag == 'dispatched'))) {
                    $checkItemlifecycle = ItemLifecycle::where('item_id', $auction_item->item_id)
                                ->where('reference_id', '!=', $auction->id)
                                ->whereIn('status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                                ->where('type', 'auction')
                                ->first();

                    if ($checkItemlifecycle != null) {
                        $is_sold_in_first_auction = 'yes';
                    }
                }

                if ($itemDetail != null && $itemDetail->status != Item::_DECLINED_ && $is_sold_in_first_auction != 'yes' && $is_exist_in_first_auction != 'yes') {
                    if ($itemDetail->is_cataloguing_approved === 'Y') {
                        $catalogue_color = 'green';
                    }
                    if ($itemDetail->lifecycle_id > 0) {
                        $valuation_letter = 'V';
                        $valuation_color = 'orange';
                        if ($itemDetail->is_valuation_approved === 'Y') {
                            $valuation_color = 'green';
                        }
                    }
                    if ($itemDetail->fee_type != null && strlen($itemDetail->fee_type) > 0) {
                        $fee_structure_letter = 'F';
                        $fee_structure_color = 'orange';
                        if ($itemDetail->is_fee_structure_approved === 'Y') {
                            $fee_structure_color = 'green';
                        }
                    }
                    if ($itemDetail->permission_to_sell === 'Y') {
                        $permission_to_sell_letter = 'P';
                        $permission_to_sell_color = 'green';
                    }

                    $seller = isset($itemDetail->customer)? $itemDetail->customer->fullname:'';

                    $condition = '';
                    if ($itemDetail->condition != null) {
                        $condition = Item::getConditionValue($itemDetail->condition);
                        if($itemDetail->condition == 'specific_condition' || $itemDetail->condition == 'general_condition') {
                            $condition = $itemDetail->specific_condition_value;
                        }
                    }

                    $preauction_items[] = [
                        'item_id' => $auction_item->item_id,
                        'item_image' => ($photo != null)?$photo->image_path:asset('images/default.jpg'),
                        'item_image_full' => ($photo != null)?$photo->full_path:null,
                        'lot_number' => $auction_item->lot_number,
                        'item_name' => $itemDetail->name,
                        'item_number' => $itemDetail->item_number,
                        'seller_id' => $itemDetail->customer_id,
                        'seller' => $seller,
                        'starting_bid' => '$ '.$starting_bid.' SGD',
                        'catalogue_letter' => $catalogue_letter,
                        'catalogue_color' => $catalogue_color,
                        'valuation_letter' => $valuation_letter,
                        'valuation_color' => $valuation_color,
                        'fee_structure_letter' => $fee_structure_letter,
                        'fee_structure_color' => $fee_structure_color,
                        'permission_to_sell_letter' => $permission_to_sell_letter,
                        'permission_to_sell_color' => $permission_to_sell_color,
                        'recently_consigned' => $itemDetail->recently_consigned
                    ];

                    $generate_label_items[] = [
                        'title' => $auction->title,
                        'close_date' => date_format(date_create($auction->timed_first_lot_ends), 'jS F Y').' | from '.date_format(date_create($auction->timed_first_lot_ends), 'h:i A'),
                        'lot_number' => $auction_item->lot_number,
                        'item_title' => $itemDetail->name,
                        'estimate' => '$'.$itemDetail->low_estimate.' - $'.$itemDetail->high_estimate.' SGD',
                        'starting_bid' => '$'.$starting_bid.' SGD',
                        'item_number' => $itemDetail->item_number,
                    ];

                    $generate_catalogue_items['results'][] = [
                        'item_image' => ($photo != null)?$photo->image_path:asset('images/default.jpg'),
                        'item_image_full' => ($photo != null)?$photo->full_path:null,
                        'item_name' => $itemDetail->name,
                        'item_description' => $itemDetail->long_description,
                        'starting_bid' => '$ '.$starting_bid.' SGD',
                        'dimension' => $itemDetail->dimensions,
                        'item_condition' => $condition,
                        'estimate' => '$ '.$itemDetail->low_estimate.' - $ '.$itemDetail->high_estimate.' SGD',
                        'lot_number' =>  $auction_item->lot_number ?? 'N/A'
                    ];

                    $reorder_lots[] = [
                        'id' => $auction_item->id,
                        'item_id' => $auction_item->item_id,
                        'item_image' => ($photo != null)?$photo->image_path:asset('images/default.jpg'),
                        'item_image_full' => ($photo != null)?$photo->full_path:null,
                        'item_name' => $itemDetail->name,
                        'low_estimate' => '$ '.$itemDetail->low_estimate.' SGD',
                        'high_estimate' => '$ '.$itemDetail->high_estimate.' SGD',
                        'starting_bid' => '$ '.$starting_bid.' SGD',
                    ];

                    $total_starting_bid = $total_starting_bid + (float)$starting_bid;
                    $total_low_estimate = $total_low_estimate + (float)$itemDetail->low_estimate;
                    $total_high_estimate = $total_high_estimate + (float)$itemDetail->high_estimate;
                    $total_lot_count ++;
                }
            }
        }

        $data = [];
        if ($type == null) {
            $data = $preauction_items;
        }
        if ($type == 'generate_label') {
            $data = $generate_label_items;
        }
        if ($type == 'generate_catalogue') {
            $data = $generate_catalogue_items;
        }
        if ($type == 'lot_reorder') {
            $data = $reorder_lots;
        }
        if ($type == 'total_starting_bid') {
            $data = number_format($total_starting_bid);
        }
        if ($type == 'total_low_estimate') {
            $data = number_format($total_low_estimate);
        }
        if ($type == 'total_high_estimate') {
            $data = number_format($total_high_estimate);
        }
        if ($type == 'total_count') {
            $data = $total_lot_count;
        }
        if ($type == 'pre_auction_data') {
            $data['preauction_items'] = $preauction_items;
            $data['total_starting_bid'] = number_format($total_starting_bid);
            $data['total_low_estimate'] = number_format($total_low_estimate);
            $data['total_high_estimate'] = number_format($total_high_estimate);
            $data['total_count'] = $total_lot_count;
        }
        return $data;
    }

    public function getNoPermissionItems($auction_id)
    {
        $no_permission_items = AuctionItem::where('auction_items.auction_id', $auction_id)
                ->join('items', 'items.id', 'auction_items.item_id')
                ->where(function ($query) {
                    $query->where('items.permission_to_sell', '!=', 'Y')
                          ->orWhere('items.is_cataloguing_approved', '!=', 'Y');
                })
                ->where('items.status', '!=', Item::_DECLINED_)
                ->whereNull('items.deleted_at')
                ->select('auction_items.item_id', 'items.name', 'items.item_number', 'items.low_estimate', 'items.customer_id', 'items.high_estimate')
                ->orderBy('auction_items.sequence_number')
                ->orderBy('auction_items.lot_number')
                ->get();

        return $no_permission_items;
    }

    public function getAllPureAuctionItems($auction_id)
    {
        $auction_items = AuctionItem::where('auction_items.auction_id', $auction_id)
                        ->whereNull('auction_items.lot_id')
                        ->join('items', 'items.id', 'auction_items.item_id')
                        ->whereNull('items.deleted_at')
                        ->orderBy('auction_items.sequence_number')
                        ->orderBy('auction_items.lot_number')
                        ->get();

        return $auction_items;
    }

    public function getLotsForClosedAuction($auction_id)
    {
        $auction_items = AuctionItem::whereNull('auction_items.deleted_at')
                ->where('auction_items.auction_id', $auction_id)
                ->whereNotNull('auction_items.lot_id')
                ->whereNotNull('auction_items.status')
                ->join('items', 'items.id', 'auction_items.item_id')
                ->where('items.status', '!=', Item::_DECLINED_)
                ->whereNull('items.deleted_at')
                ->select(
                    'auction_items.auction_id',
                    'auction_items.item_id',
                    'auction_items.status as itemstatus',
                    'items.customer_id',
                    'items.buyer_id',
                    'items.category_id'
                )
                ->orderBy('auction_items.sequence_number')
                ->orderBy('auction_items.lot_number')
                ->get();

        $lots = [];
        if (count($auction_items) > 0) {
            foreach ($auction_items as $key => $auction_item) {
                $seller_name = '';
                $seller_id = '';
                if ($auction_item->customer_id > 0) {
                    $seller_id = $auction_item->customer_id;
                    $seller_info = Customer::find($seller_id);
                    if ($seller_info != null) {
                        $seller_name = $seller_info->fullname;
                    }
                }

                $category = '';
                if ($auction_item->category_id > 0) {
                    $category_info = Category::find($auction_item->category_id);
                    if ($category_info != null) {
                        $category = $category_info->name;
                    }
                }

                $itemDetail = Item::find($auction_item->item_id);
                if ($itemDetail != null) {
                    $lots[] = [
                        'item_id' => $auction_item->item_id,
                        'item_name' => $itemDetail->name,
                        'item_number' => $itemDetail->item_number,
                        'item_status' => $auction_item->itemstatus,
                        'permission_to_sell' => $itemDetail->permission_to_sell,
                        'seller_id' => $auction_item->customer_id,
                        'seller' => $seller_name,
                        'category' => $category,
                    ];
                }
            }
        }
        // dd($lots);

        return $lots;
    }

    public function generateSellerReportOld($auction, $sellerID, $status = null)
    {
        $itemIDs = ItemHistory::where('auction_id', $auction->id)
            ->where('customer_id', $sellerID)
            ->pluck('item_id');

        $item_histories = ItemHistory::where('type','auction')->whereIn('item_id', $itemIDs)->get();

        $data = [];
        $unsoldItems = [];
        foreach ($item_histories as $key => $item_history) {
            $item = Item::find($item_history->item_id);
            $item_lifecycles = $item->itemlifecycles->where('type', 'auction')->first();
            $auction_item = AuctionItem::where('item_id', $item->id)->where('auction_id', $auction->id)->whereNotNull('lot_id')->first();

            $ar_statuses = [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_UNSOLD_];
            if ($item_history->type == 'auction' && in_array($item_history->status, $ar_statuses)) {
                $data['auction_results'][] = [
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'item_number' => $item->item_number,
                    'lot_number' => $auction_item->lot_number ?? 'N/A',
                    'opening_bid' => (isset($item_lifecycles) && isset($item_lifecycles['price']))?number_format($item_lifecycles['price']):0.00,
                    'estimate' => '$' . number_format($item->low_estimate) . ' - $'. number_format($item->high_estimate),
                    'auction_id' => $item_history->auction_id,
                    'auction_name' => isset($auction) ? $auction->title : null,
                    'auction_end_date' => isset($auction) ? date('l j F, Y', strtotime($auction->timed_first_lot_ends)) : null,
                    'buyer_id' => $item_history->buyer_id,
                    'price' => $item_history->price,
                    'sold_price' => $item->sold_price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
                if($item_history->status == Item::_UNSOLD_){
                    $unsoldItems[] = $item_history->item_id;
                }
            }
        }

        $item_histories_for_unsold = ItemHistory::where('type','lifecycle')->whereIn('item_id', $unsoldItems)->orderBy('created_at', 'desc')->get()->unique('item_id');

        foreach ($item_histories_for_unsold as $key => $item_history) {
            $item = Item::find($item_history->item_id);
            $auction_item = AuctionItem::where('item_id', $item->id)->where('auction_id', $auction->id)->whereNotNull('lot_id')->first();
            $lifecycle_statues = [Item::_AUCTION_, Item::_MARKETPLACE_, Item::_CLEARANCE_, Item::_STORAGE_];
            if ($item_history->type == 'lifecycle' && in_array($item_history->status, $lifecycle_statues)) {
                $data['notifications'][] = [
                'item_id' => $item_history->item_id,
                'item_name' => $item->name,
                'item_number' => $item->item_number,
                'lot_number' => $auction_item->lot_number ?? 'N/A',
                'auction_id' => $item_history->auction_id,
                'auction_name' => isset($auction) ? $auction->title : null,
                'price' => $item_history->price,
                'type' => $item_history->type,
                'status' => $item->lifecycle_status,
            ];
            }
        }

        return $data;
    }

    public function generateSellerReport($auction, $sellerID, $status = null)
    {
        $item_histories = ItemHistory::where('auction_id', $auction->id)
            ->where('type','auction')
            ->where('customer_id', $sellerID)
            ->when($status != null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('item_id')
            ->get();

        $data = [];
        $unsoldItemIDs = [];
        foreach ($item_histories as $key => $item_history) {
            $item = Item::find($item_history->item_id);
            $item_lifecycles = $item->itemlifecycles->where('type', 'auction')->where('reference_id', $auction->id)->first();
            $auction_item = AuctionItem::where('item_id', $item->id)->where('auction_id', $auction->id)->whereNotNull('lot_id')->first();

            $ar_statuses = [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_UNSOLD_];
            if ($item != null && $auction_item != null && $item_history->type == 'auction' && in_array($item_history->status, $ar_statuses)) {
                $data['auction_results'][] = [
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'item_number' => $item->item_number,
                    'lot_number' => $auction_item->lot_number ?? 'N/A',
                    'opening_bid' => (isset($item_lifecycles) && isset($item_lifecycles['price']))?number_format($item_lifecycles['price']):0.00,
                    'estimate' => '$' . number_format($item->low_estimate) . ' - $'. number_format($item->high_estimate),
                    'auction_id' => $item_history->auction_id,
                    'auction_name' => isset($auction) ? $auction->title : null,
                    'auction_end_date' => isset($auction) ? date('l j F, Y', strtotime($auction->timed_first_lot_ends)) : null,
                    'buyer_id' => $item_history->buyer_id,
                    'price' => $item_history->price,
                    'sold_price' => $item->sold_price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
                if($item_history->status == Item::_UNSOLD_){
                    $unsoldItemIDs[] = $item_history->item_id;
                }
            }
        }


        $unsold_items = Item::whereIn('id',$unsoldItemIDs)->orderBy('id')->get();

        foreach ($unsold_items as $key => $item) {
            $auction_item = AuctionItem::where('item_id', $item->id)->where('auction_id', $auction->id)->whereNotNull('lot_id')->first();

            if($item->lifecycle_status === Item::_AUCTION_){
                $item_lifecycle = ItemLifecycle::where('item_id',$item->id)->where('type', strtolower($item->lifecycle_status))->where('reference_id','!=',$auction->id)->first();
            }else{
                $item_lifecycle = ItemLifecycle::where('item_id',$item->id)->where('type', strtolower($item->lifecycle_status))->first();
            }

            $lifecycle_statuses = [Item::_AUCTION_, Item::_MARKETPLACE_, Item::_CLEARANCE_, Item::_STORAGE_];
            if ($auction_item != null && $item_lifecycle != null && in_array($item->lifecycle_status, $lifecycle_statuses)) {

                $lifecycle_auction = Auction::find($item_lifecycle->reference_id);

                $data['notifications'][] = [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'item_number' => $item->item_number,
                    'lot_number' => $auction_item->lot_number ?? 'N/A',
                    'auction_id' => isset($lifecycle_auction) ? $lifecycle_auction->id : null,
                    'auction_name' => isset($lifecycle_auction) ? $lifecycle_auction->title : null,
                    'price' => ($item_lifecycle)?$item_lifecycle->price:null,
                    'type' => ($item_lifecycle)?$item_lifecycle->type:null,
                    'status' => $item->lifecycle_status,
                ];
            }
        }

        return $data;
    }

    public function getAuctionTotalSettlement($auction)
    {
        $total = 0;
        $getSettlements = CustomerInvoice::where('auction_id', $auction->id)
                        ->where('type', 'bill')->get();
        foreach ($getSettlements as $key => $value) {
            $total += $value->invoice_amount;
        }

        $auctionItems = DB::table('auction_items')->where('auction_items.auction_id', $auction->id)
        ->whereNotNull('auction_items.lot_id')
        ->whereNotNull('auction_items.status')
        ->join('items', 'items.id', 'auction_items.item_id')
        ->where('items.status', '!=', Item::_DECLINED_)
        ->whereNull('items.deleted_at')
        ->select('items.id as item_id')
        ->get();

        $itemFeeStructures = ItemFeeStructure::whereIn('item_id', $auctionItems->pluck('item_id'))->get();
        $oldTotal = $itemFeeStructures->sum('fee_total');
        return $total;
    }

    public function generateKycReport($auction)
    {
        $sellers = [];

        $item_ids = AuctionItem::where('auction_id',$auction->id)->pluck('item_id')->all();
        \Log::info('generateKycReport item count : '.count($item_ids) );

        if(count($item_ids) > 0){
            $customer_ids = Item::whereIn('items.id',$item_ids)
                    ->join('customers', 'customers.id', 'items.customer_id')
                    ->select('items.customer_id')
                    ->groupBy('customer_id')
                    ->pluck('customer_id')->all();

            \Log::info('generateKycReport Sellers count : '.count($customer_ids) );

            foreach ($customer_ids as $key => $customer_id) {
                $customer = Customer::find($customer_id);
                if($customer){
                    $kycstatus = 'Incomplete';
                    if($customer->kyc_status == 'complete'){
                        $kycstatus = 'Complete';
                    }
                    if($customer->is_kyc_approved == 'Y'){
                        $kycstatus = 'Approved';
                    }
                    $sellers[] = [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'ref_no' => $customer->ref_no,
                        'status' => $kycstatus,
                    ];
                }
            }
        }

        return $sellers;
    }
}
