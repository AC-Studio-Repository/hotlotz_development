<?php

namespace App\Modules\Item\Http\Repositories;

use App\Helpers\NHelpers;
use App\Events\ItemCreatedEvent;
use App\Events\ItemUpdatedEvent;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use Illuminate\Support\Facades\Storage;
use App\Modules\Item\Models\AuctionItem;
use App\Events\Item\CreateThumbnailEvent;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\ItemFeeStructure;
use App\Modules\Auction\Models\Auction;

class ItemRepository
{
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0)
    {
        return $this->item
                    ->orderBy('registration_date', 'DESC')
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
        return $this->item
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
        $new = $this->item->create($payload);
        event(new ItemCreatedEvent($new));
        return $new;
    }

    public function update($id, $payload, $withTrash = false, $status)
    {
        $updated = $this->item
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);

        event(new ItemUpdatedEvent($id, $status));

        return $updated;
    }

    public function canDestroy($id)
    {
        return $this->item->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1)
    { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->item->destroy($id);
        } else {
            return $this->item->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id)
    {
        return $this->item->withTrashed()->find($id)->restore();
    }

    public function getPayloadForDuplicateItem($item, $type = null)
    {
        $payload = [];
        $itemcollection = collect($item);
        foreach ($itemcollection as $key=>$value) {
            if ($key != 'id') {
                $payload[$key] = $value;

                if($type == null){
                    // $count = Item::where('title', $item->title)->count();
                    if ($key == 'name' || $key == 'title') { //mod: by mct [4June22] - reference by "Sophy Meeting Notes - 27 May"
                        // $name = $item->title .'_'. ($count+1);
                        $name = 'Duplicate of '.$item->name; //mod: by mct [4June22] - reference by "Sophy Meeting Notes - 27 May"
                        $payload[$key] = $name;
                    }

                    $itemcode_data = Item::generateItemCode($item->customer_id);
                    if ($key == 'item_number') {
                        $payload[$key] = $itemcode_data['item_code'];
                    }
                    if ($key == 'item_code_id') {
                        $payload[$key] = $itemcode_data['item_code_id'];
                    }
                }
                if($type == 'CreditNote'){
                    $item_number = $item->item_number;
                    $arr1 = explode('/', $item->item_number);
                    $arr2 = explode('.', $arr1[1]);
                    if($item->item_code_id == null && count($arr2)>0){
                        $item_number = $arr1[0].'/'.$arr2[0].'.'.((int)$arr2[1] + 1);
                    }else{
                        $item_number = $item->item_number . '.1';
                    }

                    if ($key == 'item_number') {
                        $payload[$key] = $item_number;
                    }
                    if ($key == 'item_code_id') {
                        $payload[$key] = null;
                    }
                }

                ## Start - added by mct [4June22] - reference by "Sophy Meeting Notes - 27 May"
                if ($key == 'long_description'){
                    $payload[$key] = '.';
                }
                if ($key == 'sub_category' || $key == 'category_data') {
                    $payload[$key] = null;
                }

                if ($key == 'condition') {
                    $payload[$key] = 'general_condition';
                }
                if ($key == 'specific_condition_value') {
                    $payload[$key] = Item::getConditionSolution('general_condition');
                }

                if ($key == 'dimensions' || $key == 'weight' || $key == 'additional_notes' || $key == 'internal_notes') {
                    $payload[$key] = null;
                }
                ## End - added by mct [4June22]

                if ($key == 'registration_date') {
                    $payload[$key] = date('Y-m-d H:i:s');
                }

                if ($key == 'status') {
                    $payload[$key] = Item::_PENDING_;
                }
                if ($key == 'lifecycle_status' || $key == 'tag') {
                    $payload[$key] = null;
                }

                if ($key == 'permission_to_sell') {
                    $payload[$key] = 'N';
                }

                if ($key == 'cataloguing_needed') {
                    $payload[$key] = 'Y';
                }
                if ($key == 'cataloguing_approver_id') {
                    $payload[$key] = null;
                }
                if ($key == 'is_cataloguing_approved') {
                    $payload[$key] = 'N';
                }

                if ($key == 'valuation_approver_id') {
                    $payload[$key] = null;
                }
                if ($key == 'is_valuation_approved') {
                    $payload[$key] = 'N';
                }

                if ($key == 'fee_structure_approver_id') {
                    $payload[$key] = null;
                }
                if ($key == 'is_fee_structure_approved') {
                    $payload[$key] = 'N';
                }
                if ($key == 'is_fee_structure_needed') {
                    $payload[$key] = 'Y';
                }

                if ( in_array($key, [
                    'consignment_flag','xero_item_id','invoice_id','bill_id',
                    'seller_agreement_signed_date','saleroom_receipt_date',
                    'entered_auction1_date','entered_auction2_date','entered_marketplace_date','entered_clearance_date',
                    'sold_price','sold_price_inclusive_gst','sold_price_exclusive_gst',
                    'sold_date','buyer_id','settled_date','paid_date',
                    'dispatched_or_collected_date','dispatched_person','dispatched_remark',
                    'withdrawn_date','storage_date','declined_date',
                    'pending_flag','declined_flag','in_auction_flag','in_marketplace_flag',
                    'sold_flag','settled_flag','paid_flag','withdrawn_flag','storage_flag',
                    'to_be_collected_flag','dispatched_flag',
                    'cataloguing_approval_date','valuation_approval_date','fee_structure_approval_date',
                    'cancel_sale_date','private_sale_type','private_sale_auction_id','private_sale_price','private_sale_date','private_sale_buyer_premium',
                    'delivery_requested','delivery_requested_date','delivery_booked_date',
                    'internal_withdraw_date','storage_email1_date','storage_email2_date',
                    'is_credit_noted','is_credit_note_item','credit_note_date'
                ]) ) {
                    $payload[$key] = null;
                }

                if($type == 'CreditNote' && ($key == 'is_credit_note_item' || $key == 'credit_note_date')){
                    $payload['is_credit_note_item'] = 'Y';
                    $payload['credit_note_date'] = date('Y-m-d H:i:s');
                }
            }
        }

        return $payload;
    }

    public function cloneImage($item_id, $new_item_id, $type = null)
    {
        if($type == null){
            $new_item_image = [
                'item_id' => $new_item_id,
                'file_name' => "ComingSoon.png",
                'file_path' => asset('images/ComingSoon.png'),
                'full_path' => asset('images/ComingSoon.png'),
            ];
            ItemImage::create($new_item_image);
            event(new CreateThumbnailEvent($new_item_id));
        }

        if($type == 'CreditNote'){
            $item_images = ItemImage::where('item_id', $item_id)->get();

            foreach ($item_images as $key => $item_image) {
                $new_path = 'item/'.$new_item_id;

                if( !Storage::exists($item_image->file_path) ) {
                    $new_item_image = [
                        'item_id' => $new_item_id,
                        'file_name' => "ComingSoon.png",
                        'file_path' => asset('images/ComingSoon.png'),
                        'full_path' => asset('images/ComingSoon.png'),
                    ];
                    ItemImage::create($new_item_image);
                }

                if( Storage::exists($item_image->file_path ) ) {
                    $fileContent = Storage::get($item_image->file_path);
                    $name = (string) \Str::uuid();
                    $path_parts = pathinfo($item_image->file_path);
                    \Log::info('duplicateItem - path_parts : '.print_r($path_parts, true));

                    $extension = $path_parts['extension'];
                    $file_name = $item_image->file_name;
                    $new_file_path = $new_path.'/'.$file_name;
                    Storage::put($new_file_path, $fileContent);
                    \Log::info('duplicateItem - new_file_path : '.print_r($new_file_path, true));

                    $new_full_path = Storage::url($new_file_path);
                    \Log::info('duplicateItem - new_full_path : '.print_r($new_full_path, true));

                    $new_item_image = [
                        'item_id' => $new_item_id,
                        'file_name' => $file_name,
                        'file_path' => $new_file_path,
                        'full_path' => $new_full_path,
                    ];
                    ItemImage::create($new_item_image);
                }
            }
            event(new CreateThumbnailEvent($new_item_id));
        }
    }

    public function cloneLifecycle($item_id, $new_item_id)
    {
        $item_lifecycles = ItemLifecycle::where('item_id', $item_id)->get();
        foreach ($item_lifecycles as $key => $item_lifecycle) {
            $new_item_lifecycle = [
                "item_id" => $new_item_id,
                "type" => $item_lifecycle->type,
                "price" => $item_lifecycle->price,
                "period" => $item_lifecycle->period,
                "second_period" => $item_lifecycle->second_period,
                "is_indefinite_period" => $item_lifecycle->is_indefinite_period,
                "reference_id" => $item_lifecycle->reference_id,
                "status" => null,
                "action" => null,
                "entered_date" => null,
                "sold_date" => null,
                "sold_price" => null,
                "buyer_id" => null,
                "withdrawn_date" => null,
            ];
            ItemLifecycle::create($new_item_lifecycle);
        }

        $auction_items = AuctionItem::where('item_id', $item_id)->get();
        foreach ($auction_items as $key => $auction_item) {
            $new_auction_item = [
                "auction_id" => $auction_item->auction_id,
                "item_id" => $new_item_id,
                "status" => null,
                "lot_id" => null,
                "lot_number" => null,
                "sr_status" => null,
                "is_lot_ended" => null,
                "end_time_utc" => null,
                "sold_date" => null,
                "sold_price" => null,
                "buyer_id" => null,
                "sr_lot_data" => null,
                "sr_sale_result" => null,
            ];
            $auctionitem = AuctionItem::create($new_auction_item);
            $auctionitem->sequence_number = $auctionitem->id;
            $auctionitem->lot_number = $auctionitem->id;
            $auctionitem->save();
        }
    }

    public function cloneFeeStructure($item_id, $new_item_id)
    {
        $item_fee_structures = ItemFeeStructure::where('item_id', $item_id)->get();
        foreach ($item_fee_structures as $key => $item_fee_structure) {
            $new_item_fee_structure = [
                "item_id" => $new_item_id,
                "fee_type" => $item_fee_structure->fee_type,
                "sales_commission" => $item_fee_structure->sales_commission,
                "fixed_cost_sales_fee" => $item_fee_structure->fixed_cost_sales_fee,
                "hotlotz_owned_stock" => $item_fee_structure->hotlotz_owned_stock,
                "performance_commission_setting" => $item_fee_structure->performance_commission_setting,
                "performance_commission" => $item_fee_structure->performance_commission,
                "minimum_commission_setting" => $item_fee_structure->minimum_commission_setting,
                "minimum_commission" => $item_fee_structure->minimum_commission,
                "insurance_fee_setting" => $item_fee_structure->insurance_fee_setting,
                "insurance_fee" => $item_fee_structure->insurance_fee,
                "listing_fee_setting" => $item_fee_structure->listing_fee_setting,
                "listing_fee" => $item_fee_structure->listing_fee,
                "unsold_fee_setting" => $item_fee_structure->unsold_fee_setting,
                "unsold_fee" => $item_fee_structure->unsold_fee,
                "withdrawal_fee_setting" => $item_fee_structure->withdrawal_fee_setting,
                "withdrawal_fee" => $item_fee_structure->withdrawal_fee,
            ];
            ItemFeeStructure::create($new_item_fee_structure);
        }
    }

    public function getFeeStructure($item_id)
    {
        $item_fee = ItemFeeStructure::where('item_id', $item_id)->first();
        if($item_fee != null){
            if($item_fee->sales_commission != null){
                $item_fee->sales_commission = str_replace( array("$", "%"), '', $item_fee->sales_commission);
            }
            if($item_fee->fixed_cost_sales_fee != null){
                $item_fee->fixed_cost_sales_fee = str_replace( array("$", "%", "+"), '', $item_fee->fixed_cost_sales_fee);
            }

            if($item_fee->performance_commission != null){
                $item_fee->performance_commission = str_replace( array("$", "%", "+"), '', $item_fee->performance_commission);
            }
            if($item_fee->minimum_commission != null){
                $item_fee->minimum_commission = str_replace( array("$", "%", "+"), '', $item_fee->minimum_commission);
            }
            if($item_fee->insurance_fee != null){
                $item_fee->insurance_fee = str_replace( array("$", "%", "+"), '', $item_fee->insurance_fee);
            }
            if($item_fee->listing_fee != null){
                $item_fee->listing_fee = str_replace( array("$", "%", "+"), '', $item_fee->listing_fee);
            }
            if($item_fee->unsold_fee != null){
                $item_fee->unsold_fee = str_replace( array("$", "%", "+"), '', $item_fee->unsold_fee);
            }
            if($item_fee->withdrawal_fee != null){
                $item_fee->withdrawal_fee = str_replace( array("$", "%", "+"), '', $item_fee->withdrawal_fee);
            }
            if($item_fee->ic_amount != null){
                $item_fee->ic_amount = str_replace( array("$", "%", "+"), '', $item_fee->ic_amount);
            }
        }
        return $item_fee;
    }

    public function getItemFees($request)
    {
        // dd($request->all());
        $data = [];
        $data['sales_commission_fee'] = $request->sales_commission;
        if($request->sales_commission_currency == '$'){
            $data['sales_commission_fee'] = '$'.$request->sales_commission;
        }
        if($request->sales_commission_currency == '%'){
            $data['sales_commission_fee'] = $request->sales_commission.'%';
        }

        $data['fixed_cost_sales_fee'] = $request->fixed_cost_sales_fee;
        if($request->fixed_cost_sales_fee_currency == '$'){
            $data['fixed_cost_sales_fee'] = '$'.$request->fixed_cost_sales_fee;
        }
        if($request->fixed_cost_sales_fee_currency == '%'){
            $data['fixed_cost_sales_fee'] = $request->fixed_cost_sales_fee.'%';
        }

        $data['performance_commission'] = $request->performance_commission;
        if($request->performance_commission_setting == 1 && $request->performance_commission_currency == '$'){
            $data['performance_commission'] = '$'.$request->performance_commission;
        }
        if($request->performance_commission_setting == 1 && $request->performance_commission_currency == '%'){
            $data['performance_commission'] = $request->performance_commission.'%';
        }

        $data['minimum_commission'] = $request->minimum_commission;
        if($request->minimum_commission_setting == 1 && $request->minimum_commission_currency == '$'){
            $data['minimum_commission'] = '$'.$request->minimum_commission;
        }
        if($request->minimum_commission_setting == 1 && $request->minimum_commission_currency == '%'){
            $data['minimum_commission'] = $request->minimum_commission.'%';
        }

        $data['insurance_fee'] = $request->insurance_fee;
        if($request->insurance_fee_setting == 1 && $request->insurance_fee_currency == '$'){
            $data['insurance_fee'] = '$'.$request->insurance_fee;
        }
        if($request->insurance_fee_setting == 1 && $request->insurance_fee_currency == '%'){
            $data['insurance_fee'] = $request->insurance_fee.'%';
        }

        $data['listing_fee'] = $request->listing_fee;
        if($request->listing_fee_setting == 1 && $request->listing_fee_currency == '$'){
            $data['listing_fee'] = '$'.$request->listing_fee;
        }
        if($request->listing_fee_setting == 1 && $request->listing_fee_currency == '%'){
            $data['listing_fee'] = $request->listing_fee.'%';
        }

        $data['unsold_fee'] = $request->unsold_fee;
        if($request->unsold_fee_setting == 1 && $request->unsold_fee_currency == '$'){
            $data['unsold_fee'] = '$'.$request->unsold_fee;
        }
        if($request->unsold_fee_setting == 1 && $request->unsold_fee_currency == '%'){
            $data['unsold_fee'] = $request->unsold_fee.'%';
        }

        $data['withdrawal_fee'] = $request->withdrawal_fee;
        if($request->withdrawal_fee_setting == 1 && $request->withdrawal_fee_currency == '$'){
            $data['withdrawal_fee'] = '$'.$request->withdrawal_fee;
        }
        if($request->withdrawal_fee_setting == 1 && $request->withdrawal_fee_currency == '%'){
            $data['withdrawal_fee'] = $request->withdrawal_fee.'%';
        }

        $data['ic_amount'] = $request->ic_amount;
        if($request->ic_details == 1 && $request->ic_amount_currency == '$'){
            $data['ic_amount'] = '$'.$request->ic_amount;
        }
        if($request->ic_details == 1 && $request->ic_amount_currency == '%'){
            $data['ic_amount'] = $request->ic_amount.'%';
        }

        return $data;
    }

    public function getItemLifecycleAndAuctionHistory($item)
    {
        $item_lifecycles = Item::getItemLifecycle($item->id);
        // dd($item_lifecycles);

        $itemlifecycles = [];
        $auction_histories = [];
        $lot_number = null;
        if (count($item_lifecycles) > 0) {
            foreach ($item_lifecycles as $key => $item_lifecycle) {
                $reference_id = $item_lifecycle->reference_id;
                if ($item_lifecycle->type == 'marketplace') {
                    $reference_id = explode(',', $item_lifecycle->reference_id);
                }

                $itemlifecycles[] = [
                    'id' => $item_lifecycle->id,
                    'type' => $item_lifecycle->type,
                    'price' => $item_lifecycle->price,
                    'reference_id' => $reference_id,
                    'period' => $item_lifecycle->period,
                    'second_period' => $item_lifecycle->second_period,
                    'is_indefinite_period' => $item_lifecycle->is_indefinite_period,
                    'hid_marketplace' => $item_lifecycle->reference_id,
                    'status' => $item_lifecycle->action,
                    'lifecycle_id' => $item->lifecycle_id,
                ];

                if ($item_lifecycle->type == 'auction') {
                    $auction = Auction::find($item_lifecycle->reference_id);
                    if (isset($auction)) {
                        $auction_item = AuctionItem::where('item_id', $item->id)->where('auction_id', $auction->id)->whereNotNull('lot_id')->first();
                        $bidders_list = '#';
                        if (isset($auction_item)) {
                            $bidders_list = "https://toolbox.globalauctionplatform.com/auction-" . $auction->sr_auction_id . "/lot-bids?lotID=" . $auction_item->lot_id;
                        }

                        $auction_histories[] = [
                            'name' => $auction->title,
                            'entered_date' => $item_lifecycle->entered_date,
                            'auction' => $auction,
                            'bidders_list' => $bidders_list,
                        ];

                        ## Start - Get Lot Number
                        if ($item->status == Item::_IN_AUCTION_ && $item_lifecycle->action == ItemLifecycle::_PROCESSING_ && isset($auction_item)) {
                            $lot_number = $auction_item->lot_number;
                        }
                        if (in_array($item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) && $item->lifecycle_status == Item::_AUCTION_ && isset($auction_item) && in_array($auction_item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])) {
                            $lot_number = $auction_item->lot_number;
                        }
                        ## End - Get Lot Number
                    }
                }
            }
        }

        $data = [
            'itemlifecycles' => $itemlifecycles,
            'auction_histories' => $auction_histories,
            'lot_number' => $lot_number,
        ];
        return $data;
    }

    public function getCancelSaleItemData()
    {
        $payload['status'] = Item::_PENDING_;
        $payload['lifecycle_status'] = null;
        $payload['permission_to_sell'] = 'N';
        $payload['cataloguing_needed'] = 'Y';
        $payload['cataloguing_approver_id'] = null;
        $payload['is_cataloguing_approved'] = 'N';
        $payload['valuation_approver_id'] = null;
        $payload['is_valuation_approved'] = 'N';
        $payload['fee_structure_approver_id'] = null;
        $payload['is_fee_structure_approved'] = 'N';
        $payload['is_fee_structure_needed'] = 'Y';
        $payload['lifecycle_id'] = 0;
        $payload['buyer_id'] = null;

        $payload['sold_date'] = null;
        $payload['sold_price'] = null;
        $payload['sold_price_inclusive_gst'] = null;
        $payload['sold_price_exclusive_gst'] = null;

        $payload['seller_agreement_signed_date'] = null;
        $payload['saleroom_receipt_date'] = null;
        $payload['entered_auction1_date'] = null;
        $payload['entered_auction2_date'] = null;
        $payload['entered_marketplace_date'] = null;
        $payload['entered_clearance_date'] = null;
        $payload['credit_note_date'] = date('Y-m-d H:i:s');

        #PrivateSale Item
        $payload['private_sale_type'] = null;
        $payload['private_sale_auction_id'] = null;
        $payload['private_sale_price'] = null;
        $payload['private_sale_buyer_premium'] = null;
        $payload['private_sale_date'] = null;

        #Clear Approval Dates
        $payload['cataloguing_approval_date'] = null;
        $payload['valuation_approval_date'] = null;
        $payload['fee_structure_approval_date'] = null;

        #Clear Credit Note Fields
        $payload['is_credit_noted'] = null;
        $payload['is_credit_note_item'] = null;
        $payload['credit_note_date'] = null;

        #Clear Tag
        $payload['tag'] = null;

        return $payload;
    }
}
