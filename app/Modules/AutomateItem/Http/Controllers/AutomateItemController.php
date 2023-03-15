<?php

namespace App\Modules\AutomateItem\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\Customer\Models\Customer;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\ItemFeeStructure;
use App\Events\Item\CreateThumbnailEvent;
use DB;


class AutomateItemController extends Controller
{
    protected $itemRepository;
    public function __construct(ItemRepository $itemRepository){
        $this->itemRepository = $itemRepository;
    }

    /**
     * Displays the category index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $item = app(Item::class);
        $auctions = Auction::whereNull('deleted_at')
            ->where('is_closed','!=','Y')
            ->orderby('timed_start')
            ->pluck('title','id')
            ->all();

        $select2customers = Customer::getSelect2CustomerData();

        $data = [
            'item' => $item,
            'auctions' => $auctions,
            'select2customers' => $select2customers,
        ];

        return view('automate_item::index', $data);
    }

    public function autoCreate(Request  $request)
    {
        // dd(Item::find("6f05a4df-1df1-406e-b610-cb16f07f4099"));
        for($i=1; $i<=$request->item_count; $i++){
            $this->createItem($request);
        }

        return redirect( route('automate_item.automate_items.index') );
    }

    public function createItem($request)
    {
        DB::beginTransaction();
        try {
            $payload = $this->getPayloadForAutomateItem($request);
            $new_item = $this->itemRepository->create($payload);

            $this->createItemImage($new_item->id);
            
            if($request->lifecycle_type != 'privatesale'){
                $this->createItemLifecycle($new_item->id, $request);
            }

            ItemFeeStructure::autoSaveSalesCommissionPayload($new_item->id);

            DB::commit();
            flash()->success(__('Automate create item :name has been created', ['name' => $new_item->name]));

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
        }
    }

    public function getPayloadForAutomateItem($request)
    {
        // dd($request->lifecycle_type);
        $current_datetime = date('Y-m-d H:i:s');
        $name = "TEST".uniqid();
        $auction_id = null;
        $status = Item::_IN_MARKETPLACE_;
        $lifecycle_status = Item::_MARKETPLACE_;
        if($request->lifecycle_type == 'auction'){
            $auction_id = $request->auction_id;
            $status = Item::_PENDING_IN_AUCTION_;
            $lifecycle_status = Item::_AUCTION_;
        }
        if($request->lifecycle_type == 'privatesale'){
            $status = Item::_PENDING_;
            $lifecycle_status = Item::_PRIVATE_SALE_;
        }

        $item_code_arr = Item::generateItemCode($request->seller_id);

        $data = [
            "name" => $name,
            "customer_id" => $request->seller_id,
            "category_id" => 7,
            "country_id" => 0,
            "package_id" => 0,
            "is_new" => "N",
            "fee_type" => "sales_commission",
            "lifecycle_id" => 10,
            "valuer_id" => 1,
            "status" => $status,
            "lifecycle_status" => $lifecycle_status,
            // "tag" => null,
            "title" => $name,
            "long_description" => $name.' Description',
            "item_number" => $item_code_arr['item_code'],
            "item_code_id" => $item_code_arr['item_code_id'],
            "permission_to_sell" => "Y",
            // "receipt_no" => null,
            // "end_time_utc" => null,
            "is_pro_photo_need" => "Y",
            "quantity" => 1,
            "sub_category" => "Vase",
            "category_data" => "{'Period':null,'Style':'Art Deco','Material':null,'Certification':null,'Dimensions with frame':null}",
            "cataloguing_needed" => "N",
            "low_estimate" => "100.00",
            "high_estimate" => "200.00",
            "reserve" => "60.00",
            "is_reserve" => "Y",
            // "opening_price" => null,
            // "buy_it_now_price" => null,
            "currency" => "SGD",
            "vat_tax_rate" => "7.00",
            // "buyers_premium_vat_rate" => null,
            // "buyers_premium_percent" => null,
            // "buyers_premium_ceiling" => null,
            // "internet_surcharge_vat_rate" => null,
            // "internet_surcharge_percent" => null,
            // "internet_surcharge_ceiling" => null,
            // "increment" => null,
            // "sale_section" => null,
            // "is_bulk" => 0,
            // "artist_resale_rights" => 0,
            // "sequence_number" => null,
            // "is_potentially_offensive" => 0,
            // "address1" => null,
            // "address2" => null,
            // "address3" => null,
            // "address4" => null,
            // "town_city" => null,
            // "postcode" => null,
            // "country_code" => null,
            // "county_state" => null,
            // "created_by" => 1,
            // "updated_by" => 1,
            // "deleted_by" => null,
            // "created_at" => $current_datetime,
            // "updated_at" => $current_datetime,
            // "deleted_at" => null,
            "currently_in_hotlotz_warehouse" => 0,
            "location" => "Saleroom",
            "is_hotlotz_own_stock" => "N",
            "supplier" => null,
            "purchase_cost" => null,
            "supplier_gst" => null,
            "condition" => "specific_condition",
            "specific_condition_value" => "Cracking and losses to the glaze",
            "provenance" => null,
            "designation" => null,
            "dimensions" => "24cm high x 26cm wide",
            "is_dimension" => "Y",
            "weight" => null,
            "is_weight" => "N",
            "additional_notes" => null,
            "internal_notes" => null,
            "registration_date" => $current_datetime,
            "seller_agreement_signed_date" => $current_datetime,
            "saleroom_receipt_date" => null,
            "entered_auction1_date" => null,
            "entered_auction2_date" => null,
            "entered_marketplace_date" => $current_datetime,
            "entered_clearance_date" => null,
            "sold_date" => null,
            "sold_price" => null,
            "sold_price_inclusive_gst" => null,
            "sold_price_exclusive_gst" => null,
            "buyer_id" => null,
            "settled_date" => null,
            "paid_date" => null,
            "dispatched_or_collected_date" => null,
            "dispatched_person" => null,
            "dispatched_remark" => null,
            "withdrawn_date" => null,
            "storage_date" => null,
            "declined_date" => null,
            "pending_flag" => null,
            "declined_flag" => null,
            "in_auction_flag" => null,
            "in_marketplace_flag" => null,
            "sold_flag" => null,
            "settled_flag" => null,
            "paid_flag" => null,
            "withdrawn_flag" => null,
            "storage_flag" => null,
            "to_be_collected_flag" => null,
            "dispatched_flag" => null,
            "brand" => 'TEST',
            "is_tree_planted" => "N",
            "cataloguing_approver_id" => 1,
            "is_cataloguing_approved" => "Y",
            "cataloguing_approval_date" => $current_datetime,
            "cataloguer_id" => 2,
            "valuation_approver_id" => 1,
            "is_valuation_approved" => "Y",
            "valuation_approval_date" => $current_datetime,
            "is_fee_structure_needed" => "N",
            "invoice_id" => null,
            "bill_id" => null,
            "consignment_flag" => null,
            "fee_structure_approver_id" => 1,
            "is_fee_structure_approved" => "Y",
            "fee_structure_approval_date" => $current_datetime,
            "cancel_sale_date" => null,
            "private_sale_type" => null,
            "private_sale_auction_id" => null,
            "private_sale_price" => null,
            "private_sale_date" => null,
            "private_sale_buyer_premium" => null,
            "is_highlight" => "N",
            // "xero_item_id" => null,
            "delivery_requested" => null,
            "delivery_requested_date" => null,
            "delivery_booked" => null,
            "delivery_booked_date" => null,
            "internal_withdraw_date" => null,
            // "storage_email1_date" => null,
            // "storage_email2_date" => null,
            "recently_consigned" => 0,
            "is_credit_noted" => null,
            "is_credit_note_item" => null,
            "credit_note_date" => null,
            "sale_contract_approved_name" => "Nexlabs Co., Ltd."
        ];

        if($request->lifecycle_type == 'auction') {
            $data['lifecycle_id'] = 8;
        }
        if($request->lifecycle_type == 'privatesale') {
            $data['lifecycle_id'] = 12;
        }

        return $data;
    }

    public function createItemImage($new_item_id)
    {
        $new_item_image = [
            'item_id' => $new_item_id,
            'file_name' => "ComingSoon.png",
            'file_path' => asset('images/ComingSoon.png'),
            'full_path' => asset('images/ComingSoon.png'),
        ];
        ItemImage::create($new_item_image);
        event(new CreateThumbnailEvent($new_item_id));
    }

    public function createItemLifecycle($new_item_id, $request)
    {
        $type = 'marketplace';
        $price = 180;
        $period = 30;
        $reference_id = '';
        if($request->lifecycle_type == 'auction'){
            $type = 'auction';
            $price = 60;
            $period = null;
            $reference_id = $request->auction_id;
        }

        $first_itemlifecycle = [
            "item_id" => $new_item_id,
            "type" => $type,
            "price" => $price,
            "period" => $period,
            "second_period" => null,
            "is_indefinite_period" => null,
            "reference_id" => $reference_id,
            "status" => null,
            "action" => ItemLifecycle::_PROCESSING_,
            "entered_date" => date('Y-m-d H:i:s'),
            "sold_date" => null,
            "sold_price" => null,
            "buyer_id" => null,
            "withdrawn_date" => null,
        ];
        // dd($first_itemlifecycle);
        ItemLifecycle::create($first_itemlifecycle);

        $second_itemlifecycle = $first_itemlifecycle;
        $second_itemlifecycle['type'] = 'storage';
        $second_itemlifecycle['price'] = 5;
        $second_itemlifecycle['period'] = '4';
        $second_itemlifecycle['second_period'] = '3';
        $second_itemlifecycle['is_indefinite_period'] = 'N';
        $second_itemlifecycle['reference_id'] = '';
        $second_itemlifecycle['action'] = null;
        $second_itemlifecycle['entered_date'] = null;
        // dd($second_itemlifecycle);
        ItemLifecycle::create($second_itemlifecycle);


        if($request->lifecycle_type == 'auction'){
            $new_auction_item = [
                "auction_id" => $request->auction_id,
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

    public function createItemFeeStructure($new_item_id)
    {
        $new_item_fee_structure = [
            "item_id" => $new_item_id,
            "fee_type" => 'sales_commission',
            "sales_commission" => 20,
            "fixed_cost_sales_fee" => null,
            "hotlotz_owned_stock" => null,
            "performance_commission_setting" => null,
            "performance_commission" => null,
            "minimum_commission_setting" => 1,
            "minimum_commission" => 40,
            "insurance_fee_setting" => 1,
            "insurance_fee" => 1.5,
            "listing_fee_setting" => null,
            "listing_fee" => null,
            "unsold_fee_setting" => null,
            "unsold_fee" => null,
            "withdrawal_fee_setting" => 1,
            "withdrawal_fee" => 60,
        ];
        ItemFeeStructure::create($new_item_fee_structure);
    }
}
