<?php

namespace App\Modules\Item\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Helpers\NHelpers;
use App\Models\Lifecycle;
use App\Jobs\LotDeleteJob;
use App\Jobs\LotUpdateJob;
use Illuminate\Http\Request;
use App\Events\ItemCreatedEvent;
use App\Events\ItemHistoryEvent;
use App\Modules\Item\Models\Item;
use App\Http\Controllers\Controller;
use App\Events\GAPRemoveLotImageEvent;
use App\Events\Item\DeclinedItemEvent;
use App\Events\Item\WithdrawItemEvent;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Item\Models\ItemVideo;
use App\Modules\Auction\Models\Auction;
use Illuminate\Support\Facades\Storage;
use App\Events\Item\CancelSaleItemEvent;
use App\Modules\Item\Models\AuctionItem;
use App\Events\Item\CreateThumbnailEvent;
use App\Events\Item\SellerAgreementEvent;
use App\Modules\Category\Models\Category;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\ItemLifecycle;
use App\Events\Item\RecentlyConsignedEvent;
use App\Events\Item\SaveItemImageToS3Event;
use App\Events\Item\SaveItemVideoToS3Event;
use App\Events\Xero\XeroInvoiceCancelEvent;
use App\Modules\Item\Models\ItemFeeStructure;
use App\Modules\Item\Models\ItemInternalPhoto;
use App\Events\Xero\XeroPrivateSaleInvoiceEvent;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Category\Models\CategoryProperty;
use App\Events\Item\SaveItemInternalPhotoToS3Event;
use App\Modules\Item\Http\Requests\StoreItemRequest;
use App\Modules\Item\Http\Requests\UpdateItemRequest;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\OrderSummary\Events\OrderWasCompleted;


class ItemController extends Controller
{
    protected $itemRepository;
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Displays the item index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        session()->forget('tab_name');
        $items = $this->itemRepository->all([], false, 10);
        $items_count = Item::count();
        if($items_count <= 0){
            $items_count = "N/A";
        }

        $auctions = Auction::orderBy('created_at', 'desc')->pluck('title', 'id')->all();
        $categories = Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id')->all();
        $lifecycles = Lifecycle::pluck('name', 'id')->all();
        // $select2customers = Customer::getSelect2CustomerData();

        $actions = [
            'valuation_needed'=>'Valuation needed',
            'cataloguing_needed'=>'Cataloguing needed',
            'lifecycle_needed'=>'Lifecycle needed',
            'fee_structure_needed'=>'Fee Structure needed',
            'professional_photo_needed'=>'Professional Photography needed',
            'cataloguing_approval_needed'=>'Cataloguing Approval needed',
            'valuation_approval_needed'=>'Valuation Approval needed',
            'fee_structure_approval_needed'=>'Fee Structure Approval needed',
        ];

        $statuses = [
            Item::_SWU_ => Item::_SWU_,
            Item::_PENDING_ => Item::_PENDING_,
            Item::_DECLINED_ => Item::_DECLINED_,
            Item::_PENDING_IN_AUCTION_ => Item::_PENDING_IN_AUCTION_,
            Item::_IN_AUCTION_ => Item::_IN_AUCTION_,
            Item::_IN_MARKETPLACE_ => Item::_IN_MARKETPLACE_,
            Item::_SOLD_ => Item::_SOLD_,
            Item::_UNSOLD_ => Item::_UNSOLD_,
            Item::_PAID_ => Item::_PAID_,
            Item::_SETTLED_ => Item::_SETTLED_,
            Item::_WITHDRAWN_ => Item::_WITHDRAWN_,
            // Item::_STORAGE_ => Item::_STORAGE_,
            // Item::_DISPATCHED_ => Item::_DISPATCHED_,
            Item::_ITEM_RETURNED_ => Item::_ITEM_RETURNED_,
        ];

        $tags = [''=>'--- Select Tag ---','in_storage'=>'In Storage','dispatched'=>'Dispatched'];

        return view('item::index', [
            'items' => $items,
            'items_count' => $items_count,
            'auctions' => $auctions,
            'categories' => $categories,
            'lifecycles' => $lifecycles,
            // 'select2customers' => $select2customers,
            'actions' => $actions,
            'statuses' => $statuses,
            'tags' => $tags,
        ]);
    }

    public function filter(Request $request)
    {
        // dd($request->all());
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;

            $sort_by = isset($request->sort_by)?$request->sort_by:'registration_date';
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            $query = Item::orderBy($sort_by, $sort_type)->select('items.*');

            //Filter by Category
            if (isset($request->category) && count($request->category) > 0) {
                $query->whereIn('items.category_id', $request->category);
            }

            //Filter by Action Required
            if (isset($request->action_required) && in_array('valuation_needed', $request->action_required)) {
                $query->where( function($query2) {
                    $query2->whereNull('items.low_estimate');
                    $query2->orWhereNull('items.high_estimate');
                    $query2->orWhere('items.low_estimate', '<=', 0);
                    $query2->orWhere('items.high_estimate', '<=', 0);
                });
            }
            if (isset($request->action_required) && in_array('cataloguing_needed', $request->action_required)) {
                $query->where('items.is_cataloguing_approved', '!=', 'Y');
            }
            if (isset($request->action_required) && in_array('lifecycle_needed', $request->action_required)) {
                $query->where('items.lifecycle_id', '<=', 0);
            }
            if (isset($request->action_required) && in_array('fee_structure_needed', $request->action_required)) {
                $query->whereNull('items.fee_type');
            }
            if (isset($request->action_required) && in_array('professional_photo_needed', $request->action_required)) {
                $query->where('items.is_pro_photo_need', 'Y');
            }
            if (isset($request->action_required) && in_array('cataloguing_approval_needed', $request->action_required)) {
                $query->where('items.is_cataloguing_approved', '!=', 'Y');
            }
            if (isset($request->action_required) && in_array('valuation_approval_needed', $request->action_required)) {
                $query->where('items.is_valuation_approved', '!=', 'Y');
            }
            if (isset($request->action_required) && in_array('fee_structure_approval_needed', $request->action_required)) {
                $query->where('items.is_fee_structure_approved', '!=', 'Y');
            }


            //Filter by Statuses
            if (isset($request->item_status)) {
                $query->whereIn('items.status', $request->item_status);
            }


            //Filter by Permission to sell
            if (isset($request->permission_to_sell)) {
                $query->where('items.permission_to_sell', $request->permission_to_sell);
            }

            //Filter by Auctions
            if (isset($request->auction)) {
                $query->join('item_lifecycles', function ($join) use ($request) {
                    $join->on('item_lifecycles.id', DB::raw('(SELECT item_lifecycles.id FROM item_lifecycles WHERE item_lifecycles.item_id = items.id and item_lifecycles.type = "auction" and item_lifecycles.reference_id = "'.$request->auction.'" and item_lifecycles.deleted_at is null LIMIT 1)'));
                });
            }

            //Filter by Lifecycle
            if (isset($request->lifecycle)) {
                $query->where('items.lifecycle_id', '=', $request->lifecycle);
            }

            //Filter by Seller
            if (isset($request->seller)) {
                $query->where('items.customer_id', $request->seller);
            }

            //Filter by marketplace
            if (isset($request->marketplace)) {
                if ($request->marketplace == 'marketplace_only') {
                    $query->where( function($query2) {
                        $query2->where('items.status', Item::_IN_MARKETPLACE_);
                        $query2->where('items.lifecycle_status', 'marketplace');
                    });
                }
                if ($request->marketplace == 'clearance_only') {
                    $query->where( function($query2) {
                        $query2->where('items.status', Item::_IN_MARKETPLACE_);
                        $query2->where('items.lifecycle_status', 'clearance');
                    });
                }
                if ($request->marketplace == 'marketplace_and_clearance') {
                    $query->where('items.status', Item::_IN_MARKETPLACE_);
                }
            }

            //Filter by Name/ItemNumber
            if (isset($request->search_text)) {
                $filterString = NHelpers::getStringBetween($request->search_text, ' / (', ')');
                if($filterString != ""){
                    $request->search_text = $filterString;
                    $query->where(function ($query2) use ($request) {
                        $query2->where('items.item_number', $request->search_text);
                    });
                }else{
                    $query->where(function ($query2) use ($request) {
                        $query2->where('items.name', 'LIKE', '%'.$request->search_text .'%')->orWhere('items.item_number', 'LIKE', '%'.$request->search_text .'%');
                    });
                }
            }

            //Filter by Tag
            if (isset($request->tag)) {
                if($request->tag == 'dispatched'){
                    $query->where('items.tag', $request->tag);
                }
                if($request->tag == 'in_storage'){
                    $query->whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_, Item::_STORAGE_, Item::_UNSOLD_, Item::_ITEM_RETURNED_]);
                    $query->where(function ($query2) use ($request) {
                        $query2->where('items.tag', $request->tag);
                        $query2->orWhere('items.tag', null);
                    });
                }
            }

            $items = $query->paginate($per_page);
            $items_count = $query->count();
            if($items_count <= 0){
                $items_count = "N/A";
            }

            $returnHTML = view('item::_index_table', [
                'items' => $items,
                'items_count' => $items_count,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    /**
     * Displays the create new item view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addItemFromItem()
    {
        session()->forget('fromClient');;
        session()->forget('sellerId');;
        return redirect(route('item.items.create'));
    }

    public function addItemFromClient($customer_id)
    {
        session(['fromClient'=> 'client']);
        session(['sellerId'=> $customer_id]);
        return redirect(route('item.items.create'));
    }

    public function showItemFromCustomer($item_id, $tab_name)
    {
        session(['tab_name'=>$tab_name]);
        return redirect(route('item.items.show', [Item::find($item_id)]));
    }

    public function create()
    {
        $salutations = NHelpers::getSalutations();
        $locations = Item::getLocation();
        $categories = [''=>'--- Select Category ---'] + Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id')->all();

        // $conditions = [
        //     'no_condition' => 'No obvious condition issues',
        //     'minor_signs' => 'Minor signs of wear commensurate with age and use',
        //     'specific_condition' => 'Specific condition',
        //     "ask_for_condition" => "For a condition report or further images, please contact the saleroom via hello@hotlotz.com",
        // ];
        $conditions = Item::getConditionList();
        $condition_solution = Item::getConditionSolution('general_condition');

        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');

        $cataloguers = User::pluck('name', 'id')->all();

        $customer_id = '';
        if(session()->has('fromClient') && session()->has('sellerId')){
            $customer_id = session('sellerId');
        }

        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        return view('item::create', [
            'item' => app(Item::class),
            'auctions' => Auction::pluck('title', 'id'),
            'categories' => $categories,
            'locations' => $locations,
            'salutations' => $salutations,
            'conditions' => $conditions,
            'condition_solution' => $condition_solution,
            'hide_item_image_ids' => '',
            'hide_item_video_ids' => '',
            'hide_item_internal_photo_ids' => '',
            'country_codes' => $country_codes,
            'cataloguers' => $cataloguers,
            'customer_id' => $customer_id,
            'countries' => $countries,
        ]);
    }

    /**
     * @param CreateItem $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreItemRequest $request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            ## The item number may vary upon item save depending on the availability of the item number.
            $item_code_arr = [
                'item_code'=>$request->item_number,
                'item_code_id'=>$request->item_code_id,
            ];
            $item_count = Item::where('item_number',$request->item_number)->count();
            if($item_count > 0){
                $item_code_arr = Item::generateItemCode($request->customer_id);
            }

            $payload = Item::packData($request, $item_code_arr, 'create');
            $item = $this->itemRepository->create($payload);

            if ($item) {
                \Log::info('item_id : '.$item->id);
                ItemFeeStructure::autoSaveSalesCommissionPayload($item->id);

                if ($request->hide_item_image_ids != null && strlen($request->hide_item_image_ids) > 0) {

                    $item_img_arr = explode(",", $request->hide_item_image_ids);
                    foreach ($item_img_arr as $key => $item_image_id) {
                        if (isset($item_image_id) && $item_image_id != "" && $item_image_id > 0) {

                            \Log::info('item_image_id : '.$item_image_id);

                            $item_image = ItemImage::find($item_image_id);
                            $item_image->item_id = $item->id;
                            $item_image->save();

                            \Log::info('call SaveItemImageToS3Event');
                            event( new SaveItemImageToS3Event($item->id, $item_image_id, $request->image_reorder) );
                        }
                    }
                }
                if ($request->hide_item_video_ids != null && strlen($request->hide_item_video_ids) > 0) {
                    $item_video_arr = explode(",", $request->hide_item_video_ids);
                    foreach ($item_video_arr as $key => $item_video_id) {
                        if (isset($item_video_id) && $item_video_id > 0) {

                            \Log::info('item_video_id : '.$item_video_id);

                            $item_video = ItemVideo::find($item_video_id);
                            $item_video->item_id = $item->id;
                            $item_video->save();

                            \Log::info('call SaveItemVideoToS3Event');
                            event( new SaveItemVideoToS3Event($item->id, $item_video_id) );
                        }
                    }
                }
                if ($request->hide_item_internal_photo_ids != null && strlen($request->hide_item_internal_photo_ids) > 0) {
                    $item_internal_photo_arr = explode(",", $request->hide_item_internal_photo_ids);
                    foreach ($item_internal_photo_arr as $key => $item_internal_photo_id) {
                        if (isset($item_internal_photo_id) && $item_internal_photo_id > 0) {

                            \Log::info('item_internal_photo_id : '.$item_internal_photo_id);

                            $item_internal_photo = ItemInternalPhoto::find($item_internal_photo_id);
                            $item_internal_photo->item_id = $item->id;
                            $item_internal_photo->save();

                            \Log::info('call SaveItemInternalPhotoToS3Event');
                            event( new SaveItemInternalPhotoToS3Event($item->id, $item_internal_photo_id) );
                        }
                    }
                }

                DB::commit();
                flash()->success(__(':title has been created', ['title' => Item::getNameById($item->id)]));
                return redirect(route('item.items.show_item', [$item, 'cataloguing' ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Item Create Failed']));
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * @param Item $item
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Item $item)
    {
        return redirect(route('item.items.edit_item', [$item, 'cataloguing' ]));

        if (!session()->has('tab_name') || session('tab_name')=='undefined') {
            session(['tab_name'=>'cataloguing']);
        }
        // dd(session('tab_name'));
        $gst_rate = 0;
        if (isset($item->customer_id) && $item->customer_id > 0) {
            if (isset($item->customer) && $item->customer->seller_gst_registered == 1) {
                $gst_rate = 7;
            }
        }

        $auctions = Auction::whereNotNull('sr_auction_id')->where('is_published','!=','Y')->where('is_closed','!=','Y')->orderBy('created_at', 'desc')->pluck('title', 'id');

        $cataloguers = User::pluck('name', 'id')->all();
        $valuers = User::pluck('name', 'id')->all();
        $approvers = User::pluck('name', 'id')->all();

        $salutations = NHelpers::getSalutations();
        $categories = [''=>'--- Select Category ---'] + Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id')->all();

        $item_image_datas = Item::getItemImageData($item->id);
        $item_video_datas = Item::getItemVideoData($item->id);
        $item_internal_photo_datas = Item::getItemInternalPhotoData($item->id);
        // dd($item_internal_photo_datas);

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
                    'id'=>$item_lifecycle->id,
                    // 'item_id'=>$item->id,
                    'type'=>$item_lifecycle->type,
                    'price'=>$item_lifecycle->price,
                    'reference_id'=>$reference_id,
                    'period'=>$item_lifecycle->period,
                    'second_period'=>$item_lifecycle->second_period,
                    'is_indefinite_period'=>$item_lifecycle->is_indefinite_period,
                    'hid_marketplace'=>$item_lifecycle->reference_id,
                    'status'=>$item_lifecycle->action,
                ];

                if ($item_lifecycle->type == 'auction') {
                    $auction = Auction::find($item_lifecycle->reference_id);
                    if (isset($auction)) {
                        $auction_item = AuctionItem::where('item_id', $item->id)->where('auction_id', $auction->id)->whereNotNull('lot_id')->first();
                        $bidders_list = '#';
                        if (isset($auction_item)) {
                            $bidders_list = "https://toolbox.globalauctionplatform.com/auction-".$auction->sr_auction_id."/lot-bids?lotID=".$auction_item->lot_id;
                        }

                        $auction_histories[] = [
                            'name' => $auction->title,
                            'entered_date' => $item_lifecycle->entered_date,
                            'auction' => $auction,
                            'bidders_list' => $bidders_list,
                        ];

                        ## Start - Get Lot Number
                        if($item->status == Item::_IN_AUCTION_ && $item_lifecycle->action == ItemLifecycle::_PROCESSING_ && isset($auction_item)){
                            $lot_number = $auction_item->lot_number;
                        }
                        if(in_array($item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) && $item->lifecycle_status == Item::_AUCTION_ && isset($auction_item) && in_array($auction_item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) ){
                            $lot_number = $auction_item->lot_number;
                        }
                        ## End - Get Lot Number
                    }
                }
            }
        }
        // dd($itemlifecycles);

        ## Fee Structure (New Logic)##
        // $item_fee = ItemFeeStructure::where('item_id', $item->id)->first();
        // dd($item_fee);

        $item_fee = $this->itemRepository->getFeeStructure($item->id);

        $item_fee_settings = Item::getItemFeeStructureSettings($item, $item_fee);

        $lifecyclename = Lifecycle::where('id', $item->lifecycle_id)->pluck('name')->first();

        $conditions = Item::getConditionList($item->category_id);

        $item_purchase = Item::getPurchaseDetails($item);

        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');

        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();


        return view('item::edit', [
            'item' => $item,
            'cataloguers' => $cataloguers,
            'valuers' => $valuers,
            'approvers' => $approvers,
            'auctions' => $auctions,
            'item_fee' => $item_fee,
            'fee_settings' => $item_fee_settings,
            'lifecycles' => [''=>'--- Select Lifecycle ---'] + Lifecycle::pluck('name', 'id')->all(),
            'item_initialpreview' => $item_image_datas['item_initialpreview'],
            'item_initialpreviewconfig' => $item_image_datas['item_initialpreviewconfig'],
            'hide_item_image_ids' => $item_image_datas['hide_item_image_ids'],
            'item_video_initialpreview' => $item_video_datas['item_video_initialpreview'],
            'item_video_initialpreviewconfig' => $item_video_datas['item_video_initialpreviewconfig'],
            'hide_item_video_ids' => $item_video_datas['hide_item_video_ids'],
            'internal_photo_initialpreview' => $item_internal_photo_datas['internal_photo_initialpreview'],
            'internal_photo_initialpreviewconfig' => $item_internal_photo_datas['internal_photo_initialpreviewconfig'],
            'hide_item_internal_photo_ids' => $item_internal_photo_datas['hide_item_internal_photo_ids'],
            'itemlifecycles' => $itemlifecycles,
            'auction_histories' => $auction_histories,
            'lifecycle_types' => ['auction'=>Item::_AUCTION_,'marketplace'=>Item::_MARKETPLACE_,'clearance'=>Item::_CLEARANCE_,'storage'=>Item::_STORAGE_],
            'salutations'=>$salutations,
            'categories'=>$categories,
            'low_estimate'=>($item->low_estimate != null)?$item->low_estimate:0,
            'high_estimate'=>($item->high_estimate != null)?$item->high_estimate:0,
            'lifecyclename' => $lifecyclename,
            'gst_rate' => $gst_rate,
            'conditions' => $conditions,
            'item_purchase' => $item_purchase,
            'country_codes' => $country_codes,
            'customer_id' => '',
            'countries' => $countries,
            'lot_number' => $lot_number,
        ]);
    }

    public function editItem(Item $item, $tab_name)
    {
        $cataloguers = User::pluck('name', 'id')->all();
        $valuers = User::pluck('name', 'id')->all();
        $approvers = User::pluck('name', 'id')->all();
        $salutations = NHelpers::getSalutations();
        $locations = Item::getLocation();
        $categories = [''=>'--- Select Category ---'] + Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id')->all();
        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $data = [
            'item' => $item,
            'tab_name' => $tab_name,
            'cataloguers' => $cataloguers,
            'valuers' => $valuers,
            'approvers' => $approvers,
            'salutations' => $salutations,
            'locations' => $locations,
            'categories' => $categories,
            'country_codes' => $country_codes,
            'countries' => $countries,
            'customer_id' => '',
        ];
        if($tab_name == 'overview'){
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $data['auction_histories'] = $lifecycle_and_auction['auction_histories'];
            $data['lot_number'] = $lifecycle_and_auction['lot_number'];
        }
        if($tab_name == 'cataloguing'){

            $conditions = Item::getConditionList($item->condition);
            $condition_solution = Item::getConditionSolution($item->condition);

            $item_image_datas = Item::getItemImageData($item->id);
            $item_video_datas = Item::getItemVideoData($item->id);
            $item_internal_photo_datas = Item::getItemInternalPhotoData($item->id);

            $data['conditions'] = $conditions;
            $data['condition_solution'] = $condition_solution;
            $data['item_initialpreview'] = $item_image_datas['item_initialpreview'];
            $data['item_initialpreviewconfig'] = $item_image_datas['item_initialpreviewconfig'];
            $data['hide_item_image_ids'] = $item_image_datas['hide_item_image_ids'];
            $data['item_video_initialpreview'] = $item_video_datas['item_video_initialpreview'];
            $data['item_video_initialpreviewconfig'] = $item_video_datas['item_video_initialpreviewconfig'];
            $data['hide_item_video_ids'] = $item_video_datas['hide_item_video_ids'];
            $data['internal_photo_initialpreview'] = $item_internal_photo_datas['internal_photo_initialpreview'];
            $data['internal_photo_initialpreviewconfig'] = $item_internal_photo_datas['internal_photo_initialpreviewconfig'];
            $data['hide_item_internal_photo_ids'] = $item_internal_photo_datas['hide_item_internal_photo_ids'];
        }
        if($tab_name == 'item_lifecycle'){
            $lifecycles = [''=>'--- Select Lifecycle ---'] + Lifecycle::pluck('name', 'id')->all();
            $lifecycle_types = ['auction'=>'Auction','marketplace'=>'Marketplace','clearance'=>'Clearance','storage'=>'Storage'];
            $gst_rate = 0;
            if (isset($item->customer_id) && $item->customer_id > 0) {
                if (isset($item->customer) && $item->customer->seller_gst_registered == 1) {
                    $gst_rate = 7;
                }
            }

            if(in_array($item->status, [Item::_SWU_, Item::_PENDING_, Item::_DECLINED_])) {
                $auctions = Auction::whereNotNull('sr_auction_id')->where('is_published','!=','Y')->where('is_closed','!=','Y')->orderBy('created_at', 'desc')->pluck('title', 'id');
            }else{
                $auctions = Auction::whereNotNull('sr_auction_id')->orderBy('created_at', 'desc')->pluck('title', 'id');
            }

            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $data['lifecycles'] = $lifecycles;
            $data['lifecycle_types'] = $lifecycle_types;
            $data['gst_rate'] = $gst_rate;
            $data['auctions'] = $auctions;
            $data['itemlifecycles'] = $lifecycle_and_auction['itemlifecycles'];
        }
        if($tab_name == 'fee_structure'){
            $lifecyclename = Lifecycle::where('id', $item->lifecycle_id)->pluck('name')->first();
            $item_fee = $this->itemRepository->getFeeStructure($item->id);
            $item_fee_settings = Item::getItemFeeStructureSettings($item, $item_fee);
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $ic_commissioner = null;
            if($item_fee != null && $item_fee['ic_commissioner'] != null) {
                $ic_commissioner = Customer::find($item_fee['ic_commissioner']);
            }

            $data['lifecyclename'] = $lifecyclename;
            $data['item_fee'] = $item_fee;
            $data['fee_settings'] = $item_fee_settings;
            $data['itemlifecycles'] = $lifecycle_and_auction['itemlifecycles'];
            $data['ic_commissioner'] = $ic_commissioner;
        }
        if($tab_name == 'item_purchase'){
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);
            $item_purchase = Item::getPurchaseDetails($item);

            $invoice_url = '#';
            if($item->invoice_id != null){
                $invoice_id = $item->invoice_id; //for invoice
                $customerInvoiceForInvoice = CustomerInvoice::where('invoice_id', $invoice_id)->first();
                $invoice_url = $customerInvoiceForInvoice->url($customerInvoiceForInvoice->id);
            }

            $bill_url = '#';
            if($item->bill_id != null){
                $bill_id = $item->bill_id; //for bill
                $customerInvoiceForbill = CustomerInvoice::where('invoice_id', $bill_id)->first();
                $bill_url = $customerInvoiceForbill->url($customerInvoiceForbill->id);
            }

            $data['auction_histories'] = $lifecycle_and_auction['auction_histories'];
            $data['item_purchase'] = $item_purchase;
            $data['invoice_url'] = $invoice_url;
            $data['bill_url'] = $bill_url;
        }
        if($tab_name == 'item_history'){
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $data['auction_histories'] = $lifecycle_and_auction['auction_histories'];
            $data['country_codes'] = $country_codes;
        }
        // dd($data);
        return view('item::edit', $data);
    }

    /**
     * Saves updates to an existing item
     *
     * @param Item       $item
     * @param UpdateItem $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateItemRequest $request)
    {
        DB::beginTransaction();
        try {
            session(['tab_name'=> $request->tab_name]);

            ## The item number may vary upon item save depending on the availability of the item number.
            $item_code_arr = [
                'item_code'=>$request->item_number,
                'item_code_id'=>$request->item_code_id,
            ];
            $item_count = Item::where('id','!=',$id)->where('item_number',$request->item_number)->count();
            if($item_count > 0){
                $item_code_arr = Item::generateItemCode($request->customer_id);
            }

            $old_item = Item::find($id);

            $payload = Item::packData($request, $item_code_arr, 'update');

            // Update auction
            $result = $this->itemRepository->update($id, $payload, true, 'Cataloguing');

            if ($result) {

                if($request->image_reorder != null && $request->image_reorder == 'edit' && $request->hide_item_image_ids != null && strlen($request->hide_item_image_ids) > 0){

                    $hide_item_image_ids = explode(",", $request->hide_item_image_ids);
                    foreach ($hide_item_image_ids as $key => $item_image_id) {
                        if ($item_image_id != "" && $item_image_id > 0) {
                            $itemimage = ItemImage::find($item_image_id);
                            $insert_item_imgs = [
                                'item_id' => $id,
                                'file_name' => $itemimage->file_name,
                                'file_path' => $itemimage->file_path,
                                'full_path' => $itemimage->full_path,
                                'lot_image_id' => $itemimage->lot_image_id,
                            ];
                            ItemImage::create($insert_item_imgs);
                            event(new CreateThumbnailEvent($id));
                            $itemimage->forceDelete();
                        }
                    }
                }

                if ($request->sub_category != null && $request->sub_category == 'Other') {
                    $attachment = $payload['sub_category'].',';

                    Item::addOtherValueForCategoryProperty($request->category_id, 'Sub Category', $attachment);
                }

                DB::commit();

                ### Update Lot
                $updated_item = Item::find($id);
                // name,description,category_name,sub_category,brand
                if (
                    $updated_item->name != $old_item->name
                    || $updated_item->long_description != $old_item->long_description
                    || $updated_item->category_id != $old_item->category_id
                    || $updated_item->sub_category != $old_item->sub_category
                    || $updated_item->brand != $old_item->brand
                    || $updated_item->dimensions != $old_item->dimensions
                    || $updated_item->weight != $old_item->weight
                    || $updated_item->condition != $old_item->condition
                    || $updated_item->provenance != $old_item->provenance
                    || $updated_item->additional_notes != $old_item->additional_notes
                ) {

                    $auctionitems = AuctionItem::whereNull('deleted_at')->where('item_id', $id)->whereNotNull('lot_id')->get();

                    if (isset($auctionitems)) {
                        foreach ($auctionitems as $key => $auctionitem) {
                            $auction = Auction::find($auctionitem->auction_id);

                            if (isset($auction) && $auction->sr_auction_id!=null && $auction->is_closed!='Y') {
                                \Log::channel('gapLog')->info('dispatch LotUpdateJob');
                                LotUpdateJob::dispatch($id, $auctionitem->auction_id, $auction->sr_auction_id, $auctionitem->lot_id, $auctionitem->lot_number);
                            }
                        }
                    }
                }

                flash()->success(__(':title has been updated', ['title' => Item::getNameById($id)]));
                return redirect(route('item.items.show_item', [Item::find($id), 'cataloguing' ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Item Update Failed']));
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the item
     *
     * @param Item $item
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Item $item)
    {
        return redirect(route('item.items.show_item', [$item, 'overview' ]));
        // if (!session()->has('tab_name') || session('tab_name')=='undefined') {
        //     session(['tab_name'=>'overview']);
        // }
        // // dd(session()->all());

        // $cataloguers = User::pluck('name', 'id')->all();
        // $valuers = User::pluck('name', 'id')->all();
        // $approvers = User::pluck('name', 'id')->all();
        // $items = $this->itemRepository->show('id', $item->id, ['category'], false);
        // // $items->end_time_utc = NHelpers::formatDateForShow($items->end_time_utc);
        // $items->end_time_utc = date_format(date_create($items->end_time_utc), 'Y-m-d h:i A');

        // $gst_rate = 0;
        // if (isset($item->customer_id) && $item->customer_id > 0) {
        //     if (isset($item->customer) && $item->customer->seller_gst_registered == 1) {
        //         $gst_rate = 7;
        //     }
        // }

        // $buyers = Customer::pluck('firstname', 'id')->all();
        // // dd(DB::getQueryLog());

        // $salutations = NHelpers::getSalutations();
        // $categories = Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id');
        // // $subcategories = Category::where('parent_id',$items->category_id)->pluck('name','id');

        // $sub_category = CategoryProperty::where('category_id', $item->category_id)->where('key', 'Sub Category')->pluck('value')->first();
        // $sub_category = explode(',', $sub_category);

        // $subcategories = [];
        // foreach ($sub_category as $key => $value) {
        //     $subcategories[$value] = $value;
        // }

        // $item_images = ItemImage::where('item_id', $item->id)->get();
        // $item_videos = ItemVideo::where('item_id', $item->id)->get();
        // $item_internal_photos = ItemInternalPhoto::where('item_id', $item->id)->get();

        // $item_lifecycles = Item::getItemLifecycle($item->id);

        // $itemlifecycles = [];
        // $auction_histories = [];
        // $lot_number = null;
        // foreach ($item_lifecycles as $key => $item_lifecycle) {
        //     $reference_id = $item_lifecycle->reference_id;
        //     if ($item_lifecycle->type == 'marketplace') {
        //         $reference_id = explode(',', $item_lifecycle->reference_id);
        //     }

        //     $itemlifecycles[] = [
        //         'id'=>$item_lifecycle->id,
        //         // 'item_id'=>$item->id,
        //         'type'=>$item_lifecycle->type,
        //         'price'=>$item_lifecycle->price,
        //         'reference_id'=>$reference_id,
        //         'period'=>$item_lifecycle->period,
        //         'second_period'=>$item_lifecycle->second_period,
        //         'is_indefinite_period'=>$item_lifecycle->is_indefinite_period,
        //         'hid_marketplace'=>$item_lifecycle->reference_id,
        //         'status'=>$item_lifecycle->status,
        //     ];

        //     if ($item_lifecycle->type == 'auction') {
        //         $auction = Auction::find($item_lifecycle->reference_id);
        //         if (isset($auction)) {
        //             $auction_item = AuctionItem::where('item_id', $item->id)->where('auction_id', $auction->id)->whereNotNull('lot_id')->first();
        //             $bidders_list = '#';
        //             if (isset($auction_item)) {
        //                 $bidders_list = "https://toolbox.globalauctionplatform.com/auction-".$auction->sr_auction_id."/lot-bids?lotID=".$auction_item->lot_id;
        //             }

        //             $auction_histories[] = [
        //                 'name' => $auction->title,
        //                 'entered_date' => $item_lifecycle->entered_date,
        //                 'auction' => $auction,
        //                 'bidders_list' => $bidders_list,
        //             ];

        //             ## Start - Get Lot Number
        //             if($item->status == Item::_IN_AUCTION_ && $item_lifecycle->action == ItemLifecycle::_PROCESSING_ && isset($auction_item)){
        //                 $lot_number = $auction_item->lot_number;
        //             }
        //             if(in_array($item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) && $item->lifecycle_status == Item::_AUCTION_ && isset($auction_item) && in_array($auction_item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) ){
        //                 $lot_number = $auction_item->lot_number;
        //             }
        //             ## End - Get Lot Number
        //         }
        //     }
        // }
        // // dd($auction_histories);

        // $categoryproperties = CategoryProperty::where('category_id', $item->category_id)->where('key', '!=', 'Sub Category')->select('id', 'key', 'value', 'field_type', 'is_required', 'is_filter')->get();

        // # Fee Structure (New Logic)##
        // // $item_fee = ItemFeeStructure::where('item_id', $item->id)->first();
        // $item_fee = $this->itemRepository->getFeeStructure($item->id);
        // $item_fee_settings = Item::getItemFeeStructureSettings($item, $item_fee);

        // $lifecyclename = Lifecycle::where('id', $item->lifecycle_id)->pluck('name')->first();

        // $item_purchase = Item::getPurchaseDetails($item);

        // $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');

        // $conditions = [
        //     'no_condition' => 'No obvious condition issues',
        //     'minor_signs' => 'Minor signs of wear commensurate with age and use',
        //     'specific_condition' => 'Specific condition',
        //     "ask_for_condition" => "For a condition report or further images, please contact the saleroom via hello@hotlotz.com",
        // ];

        // $user = Auth::user();
        // $user_id = '';
        // if ($user->can('item approve')) {
        //     $user_id = $user->id;
        // }
        // // dd($user_id);

        // $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        // $invoice_url = '#';
        // if($item->invoice_id != null){
        //     $invoice_id = $item->invoice_id; //for invoice
        //     $customerInvoiceForInvoice = CustomerInvoice::where('invoice_id', $invoice_id)->first();
        //     $invoice_url = $customerInvoiceForInvoice->url($customerInvoiceForInvoice->id);
        // }

        // $bill_url = '#';
        // if($item->bill_id != null){
        //     $bill_id = $item->bill_id; //for bill
        //     $customerInvoiceForbill = CustomerInvoice::where('invoice_id', $bill_id)->first();
        //     $bill_url = $customerInvoiceForbill->url($customerInvoiceForbill->id);
        // }

        // $ic_commissioner = Customer::find($item_fee['ic_commissioner']);

        // return view('item::itemshow.show', [
        //     'item' => $items,
        //     'gst_rate' => $gst_rate,
        //     'cataloguers' => $cataloguers,
        //     'valuers' => $valuers,
        //     'approvers' => $approvers,
        //     'auctions' => Auction::orderBy('created_at', 'desc')->pluck('title', 'id'),
        //     'categoryproperties' => $categoryproperties,
        //     'lifecycles' => [''=>'--- Select Lifecycle ---'] + Lifecycle::pluck('name', 'id')->all(),
        //     'itemlifecycles' => $itemlifecycles,
        //     'auction_histories' => $auction_histories,
        //     'lifecycle_types' => ['auction'=>'Auction','marketplace'=>'Marketplace'],
        //     'item_images' => $item_images,
        //     'item_videos' => $item_videos,
        //     'item_internal_photos' => $item_internal_photos,
        //     'buyers'=>$buyers,
        //     'salutations'=>$salutations,
        //     'categories'=>$categories,
        //     'subcategories'=>$subcategories,
        //     'item_fee' => $item_fee,
        //     'fee_settings' => $item_fee_settings,
        //     'lifecyclename' => $lifecyclename,
        //     'item_purchase' => $item_purchase,
        //     'country_codes' => $country_codes,
        //     'conditions' => $conditions,
        //     'user_id' => $user_id,
        //     'countries' => $countries,
        //     'lot_number' => $lot_number,
        //     'invoice_url' => $invoice_url,
        //     'bill_url' => $bill_url,
        //     'ic_commissioner' => $ic_commissioner,
        // ]);
    }

    public function showItem(Item $item, $tab_name)
    {
        $cataloguers = User::pluck('name', 'id')->all();
        $valuers = User::pluck('name', 'id')->all();
        $approvers = User::pluck('name', 'id')->all();
        $salutations = NHelpers::getSalutations();
        $locations = Item::getLocation();
        $categories = [''=>'--- Select Category ---'] + Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id')->all();
        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $user = Auth::user();
        $user_id = '';
        if ($user->can('item approve')) {
            $user_id = $user->id;
        }

        $data = [
            'item' => $item,
            'tab_name' => $tab_name,
            'cataloguers' => $cataloguers,
            'valuers' => $valuers,
            'approvers' => $approvers,
            'salutations' => $salutations,
            'locations' => $locations,
            'categories' => $categories,
            'country_codes' => $country_codes,
            'countries' => $countries,
            'user_id' => $user_id,
        ];
        if($tab_name == 'overview'){
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $data['auction_histories'] = $lifecycle_and_auction['auction_histories'];
            $data['lot_number'] = $lifecycle_and_auction['lot_number'];
        }
        if($tab_name == 'cataloguing'){
            $categoryproperties = CategoryProperty::where('category_id', $item->category_id)->where('key', '!=', 'Sub Category')->select('id', 'key', 'value', 'field_type', 'is_required', 'is_filter')->get();

            $conditions = Item::getConditionList($item->condition);

            $item_images = ItemImage::where('item_id', $item->id)->get();
            $item_videos = ItemVideo::where('item_id', $item->id)->get();
            $item_internal_photos = ItemInternalPhoto::where('item_id', $item->id)->get();

            $data['categoryproperties'] = $categoryproperties;
            $data['conditions'] = $conditions;
            $data['item_images'] = $item_images;
            $data['item_videos'] = $item_videos;
            $data['item_internal_photos'] = $item_internal_photos;
        }
        if($tab_name == 'item_lifecycle'){
            $lifecycles = [''=>'--- Select Lifecycle ---'] + Lifecycle::pluck('name', 'id')->all();
            $lifecycle_types = ['auction'=>'Auction','marketplace'=>'Marketplace','clearance'=>'Clearance','storage'=>'Storage'];
            $gst_rate = 0;
            if (isset($item->customer_id) && $item->customer_id > 0) {
                if (isset($item->customer) && $item->customer->seller_gst_registered == 1) {
                    $gst_rate = 7;
                }
            }

            // if(in_array($item->status, [Item::_SWU_, Item::_PENDING_, Item::_DECLINED_])) {
            //     $auctions = Auction::whereNotNull('sr_auction_id')->where('is_published','!=','Y')->where('is_closed','!=','Y')->orderBy('created_at', 'desc')->pluck('title', 'id');
            // }else{
                $auctions = Auction::whereNotNull('sr_auction_id')->orderBy('created_at', 'desc')->pluck('title', 'id');
            // }

            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $data['lifecycles'] = $lifecycles;
            $data['lifecycle_types'] = $lifecycle_types;
            $data['gst_rate'] = $gst_rate;
            $data['auctions'] = $auctions;
            $data['itemlifecycles'] = $lifecycle_and_auction['itemlifecycles'];
        }
        if($tab_name == 'fee_structure'){
            $lifecyclename = Lifecycle::where('id', $item->lifecycle_id)->pluck('name')->first();
            $item_fee = $this->itemRepository->getFeeStructure($item->id);
            $item_fee_settings = Item::getItemFeeStructureSettings($item, $item_fee);
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $ic_commissioner = null;
            if($item_fee != null && $item_fee['ic_commissioner'] != null) {
                $ic_commissioner = Customer::find($item_fee['ic_commissioner']);
            }

            $data['lifecyclename'] = $lifecyclename;
            $data['item_fee'] = $item_fee;
            $data['fee_settings'] = $item_fee_settings;
            $data['itemlifecycles'] = $lifecycle_and_auction['itemlifecycles'];
            $data['ic_commissioner'] = $ic_commissioner;
        }
        if($tab_name == 'item_purchase'){
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);
            $item_purchase = Item::getPurchaseDetails($item);

            $invoice_url = '#';
            if($item->invoice_id != null){
                $invoice_id = $item->invoice_id; //for invoice
                $customerInvoiceForInvoice = CustomerInvoice::where('invoice_id', $invoice_id)->first();
                $invoice_url = $customerInvoiceForInvoice->url($customerInvoiceForInvoice->id);
            }

            $bill_url = '#';
            if($item->bill_id != null){
                $bill_id = $item->bill_id; //for bill
                $customerInvoiceForbill = CustomerInvoice::where('invoice_id', $bill_id)->first();
                $customerInvoiceForbill->invoice_url = null;
                $customerInvoiceForbill->save();
                $bill_url = $customerInvoiceForbill->url($customerInvoiceForbill->id);
            }

            $data['auction_histories'] = $lifecycle_and_auction['auction_histories'];
            $data['item_purchase'] = $item_purchase;
            $data['invoice_url'] = $invoice_url;
            $data['bill_url'] = $bill_url;
        }
        if($tab_name == 'item_history'){
            $lifecycle_and_auction = $this->itemRepository->getItemLifecycleAndAuctionHistory($item);

            $data['auction_histories'] = $lifecycle_and_auction['auction_histories'];
            $data['country_codes'] = $country_codes;
        }
        return view('item::itemshow.show', $data);
    }

    public function itemImageUpload($id, Request $request)
    {
        // ini_set('memory_limit', '2048M');
        try {
            $inputs = $request->all();
            if ($images = $request->file('item_image')) {
                // dd($images);

                $p1 = [];
                $p2 = [];
                $images_ids = [];

                $count_images = count($images);
                $images_ids = '';

                for ($i=0; $i < $count_images; $i++) {
                    $item_image = $images[$i];

                    if (isset($item_image)) {
                        $item_id = null;
                        if ($id != '0') {
                            $item_id = $id;

                            $file_path = Storage::put('item/'.$item_id, $item_image);
                            // $file_name = str_replace('item/'.$item_id.'/', '', $file_path);
                            $file_name = $item_image->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_item_imgs = [
                                'item_id' => $item_id,
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        } else {
                            $foldername = \Str::random('10');
                            $file_path = Storage::put('temp/'.$foldername, $item_image);
                            $file_name = $item_image->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_item_imgs = [
                                'item_id' => $item_id,
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        }
                        $item_img_id = ItemImage::insertGetId($insert_item_imgs + NHelpers::created_updated_at());
                        // dd($item_img_id);
                        event(new CreateThumbnailEvent($item_id));

                        $images_ids .= $item_img_id.',';
                        if (!($item_img_id)) {
                            echo '{}';
                            return;
                        } else {
                            $item_image_obj = ItemImage::find($item_img_id);
                            $key = '<code to parse your image key>';
                            $url = '/manage/items/'.$item_img_id.'/image_delete';
                            $p1[] = $item_image_obj->full_path; // sends the data
                            $p2[] = ['caption' => $item_image_obj->file_name, 'size' => '57071', 'width' => '263px','height' => '217px', 'url' => $url, 'key' => $item_img_id, 'extra' => ['_token'=>csrf_token(),'id'=>$item_img_id]];
                        }
                    }
                }

                $data = [
                    'status'=>1,
                    'ids'=>$images_ids,
                    'initialPreview' => $p1,
                    'initialPreviewConfig' => $p2,
                    'append' => true // whether to append these configurations to initialPreview.
                                     // if set to false it will overwrite initial preview
                                     // if set to true it will append to initial preview
                                     // if this propery not set or passed, it will default to true.
                ];
                // dd($data);

                return json_encode($data);
            }
        } catch (Exception $e) {
            return json_encode(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function itemImageDelete(Request $request, $item_image_id)
    {
        try {
            if ($item_image_id) {
                $lotimage = DB::table('lot_images')->whereNull('deleted_at')->where('item_image_id', $item_image_id)->first();

                $itemimage = ItemImage::find($item_image_id);
                if ($itemimage) {
                    if ($itemimage->thumbnail_file_path) {
                        if (Storage::exists($itemimage->thumbnail_file_path)) {
                            Storage::delete($itemimage->thumbnail_file_path);
                        }
                    }
                    Storage::delete($itemimage->file_path);
                    $itemimage->forceDelete();
                }

                DB::table('lot_images')->whereNull('deleted_at')->where('item_image_id', $item_image_id)->delete();

                ## no need [12Mar2021]
                // if (isset($lotimage->lot_image_id)) {
                //     \Log::channel('gapLog')->info('call GAPRemoveLotImageEvent');
                //     event(new GAPRemoveLotImageEvent($lotimage->lot_image_id));
                // }

                return response()->json(array('status'=>'success','message'=>'Item Image Delete successfully!','item_image_id'=>$item_image_id));
            }
            return response()->json(array('status'=>'failed','message'=>'Item Image Delete failed!','item_image_id'=>$item_image_id));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function itemVideoUpload($id, Request $request)
    {
        // ini_set('memory_limit', '2048M');
        try {
            $inputs = $request->all();
            if ($videos = $request->file('item_video')) {
                // dd($videos);

                $p1 = [];
                $p2 = [];
                $video_ids = [];

                $count_videos = count($videos);
                $video_ids = '';

                for ($i=0; $i < $count_videos; $i++) {
                    $item_video = $videos[$i];

                    if (isset($item_video)) {
                        $item_id = null;
                        if ($id != '0') {
                            $item_id = $id;

                            $file_path = Storage::put('item/'.$item_id, $item_video);
                            $file_name = $item_video->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_item_videos = [
                                'item_id' => $item_id,
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        } else {
                            $foldername = \Str::random('10');
                            $file_path = Storage::put('temp/'.$foldername, $item_video);
                            $file_name = $item_video->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_item_videos = [
                                'item_id' => $item_id,
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        }
                        $item_vid_id = ItemVideo::insertGetId($insert_item_videos + NHelpers::created_updated_at());
                        // dd($item_vid_id);


                        $video_ids .= $item_vid_id.',';
                        if (!($item_vid_id)) {
                            echo '{}';
                            return;
                        } else {
                            $item_video_obj = ItemVideo::find($item_vid_id);
                            $key = '<code to parse your video key>';
                            $url = '/manage/items/'.$item_vid_id.'/video_delete';
                            $p1[] = $item_video_obj->full_path; // sends the data
                            $p2[] = ['caption' => $item_video_obj->file_name, 'size' => '57071', 'width' => '263px','height' => '217px', 'url' => $url, 'key' => $item_vid_id, 'extra' => ['_token'=>csrf_token()]];
                        }
                    }
                }

                $data = [
                    'status'=>1,
                    'ids'=>$video_ids,
                    'initialPreview' => $p1,
                    'initialPreviewConfig' => $p2,
                    'append' => true // whether to append these configurations to initialPreview.
                                     // if set to false it will overwrite initial preview
                                     // if set to true it will append to initial preview
                                     // if this propery not set or passed, it will default to true.
                ];

                return json_encode($data);
            }
        } catch (Exception $e) {
            return json_encode(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function itemVideoDelete(Request $request, $item_video_id)
    {
        try {
            if ($item_video_id) {
                $itemvideo = ItemVideo::find($item_video_id);
                Storage::delete($itemvideo->file_path);
                $itemvideo->forceDelete();

                return response()->json(array('status'=>'success','message'=>'Item Video Delete successfully!','item_video_id'=>$item_video_id));
            }
            return response()->json(array('status'=>'failed','message'=>'Item Video Delete failed!','item_video_id'=>$item_video_id));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function itemInternalPhotoUpload($id, Request $request)
    {
        // ini_set('memory_limit', '2048M');
        try {
            $inputs = $request->all();
            if ($internal_photos = $request->file('item_internal_photo')) {
                // dd($internal_photos);

                $p1 = [];
                $p2 = [];
                $internal_photo_ids = [];

                $count_internal_photos = count($internal_photos);
                $internal_photo_ids = '';

                for ($i=0; $i < $count_internal_photos; $i++) {
                    $item_internal_photo = $internal_photos[$i];

                    if (isset($item_internal_photo)) {
                        $item_id = null;
                        if ($id != '0') {
                            $item_id = $id;

                            $file_path = Storage::put('item/'.$item_id, $item_internal_photo);
                            $file_name = $item_internal_photo->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_item_internal_photos = [
                                'item_id' => $item_id,
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        } else {
                            $foldername = \Str::random('10');
                            $file_path = Storage::put('temp/'.$foldername, $item_internal_photo);
                            $file_name = $item_internal_photo->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_item_internal_photos = [
                                'item_id' => $item_id,
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        }
                        $item_internalphoto_id = ItemInternalPhoto::insertGetId($insert_item_internal_photos + NHelpers::created_updated_at());
                        // dd($item_internalphoto_id);


                        $internal_photo_ids .= $item_internalphoto_id.',';
                        if (!($item_internalphoto_id)) {
                            echo '{}';
                            return;
                        } else {
                            $item_internal_photo_obj = ItemInternalPhoto::find($item_internalphoto_id);
                            $key = '<code to parse your image key>';
                            $url = '/manage/items/'.$item_internalphoto_id.'/internal_photo_delete';
                            $p1[] = $item_internal_photo_obj->full_path; // sends the data
                            $p2[] = ['caption' => $item_internal_photo_obj->file_name, 'size' => '57071', 'width' => '263px','height' => '217px', 'url' => $url, 'key' => $item_internalphoto_id, 'extra' => ['_token'=>csrf_token()]];
                        }
                    }
                }

                $data = [
                    'status'=>1,
                    'ids'=>$internal_photo_ids,
                    'initialPreview' => $p1,
                    'initialPreviewConfig' => $p2,
                    'append' => true // whether to append these configurations to initialPreview.
                                     // if set to false it will overwrite initial preview
                                     // if set to true it will append to initial preview
                                     // if this propery not set or passed, it will default to true.
                ];

                return json_encode($data);
            }
        } catch (Exception $e) {
            return json_encode(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function itemInternalPhotoDelete(Request $request, $item_internal_photo_id)
    {
        try {
            if ($item_internal_photo_id) {
                $item_internalphoto = ItemInternalPhoto::find($item_internal_photo_id);
                Storage::delete($item_internalphoto->file_path);
                $item_internalphoto->forceDelete();

                return response()->json(array('status'=>'success','message'=>'Item Internal Photo Delete successfully!','item_internal_photo_id'=>$item_internal_photo_id));
            }
            return response()->json(array('status'=>'failed','message'=>'Item Internal Photo Delete failed!','item_internal_photo_id'=>$item_internal_photo_id));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    /**
     * Delete a item
     *
     * @param Item $item
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Item $item)
    {
        DB::beginTransaction();
        try {

            Item::where('id', $item->id)->forceDelete();

            $item_images = ItemImage::where('item_id', $item->id)->get();
            if (isset($item_images) && count($item_images) > 0) {
                // foreach ($item_images as $item_image) {
                //     Storage::delete($item_image->file_path);
                // }
            }
            ItemImage::where('item_id', $item->id)->forceDelete();
            DB::table('lot_images')->where('item_id', $item->id)->delete();

            $item_videos = ItemVideo::where('item_id', $item->id)->get();
            if (isset($item_videos) && count($item_videos) > 0) {
                foreach ($item_videos as $item_video) {
                    Storage::delete($item_video->file_path);
                }
            }
            ItemVideo::where('item_id', $item->id)->forceDelete();

            $item_internalphotos = ItemInternalPhoto::where('item_id', $item->id)->get();
            if (isset($item_internalphotos) && count($item_internalphotos) > 0) {
                foreach ($item_internalphotos as $item_internalphoto) {
                    Storage::delete($item_internalphoto->file_path);
                }
            }
            ItemInternalPhoto::where('item_id', $item->id)->forceDelete();

            ItemLifecycle::where('item_id', $item->id)->forceDelete();
            ItemFeeStructure::where('item_id', $item->id)->forceDelete();

            $lot_ids = AuctionItem::where('item_id', $item->id)->pluck('lot_id');
            if (isset($lot_ids) && count($lot_ids) > 0) {
                foreach ($lot_ids as $key => $lot_id) {
                    \Log::channel('gapLog')->info('destroy - dispatch LotDeleteJob');
                    LotDeleteJob::dispatch($lot_id);
                }
            }
            AuctionItem::where('item_id', $item->id)->forceDelete();


            DB::commit();

            flash()->success(__(':title has been deleted', ['title' => $item->name]));
            return response()->json([ 'status'=>'success', 'message' => 'Item '.$item->name.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }

    public function itemLifecycleUpdate($item_id, Request $request)
    {
        //dd($request->all());
        try {
            DB::beginTransaction();
            session(['tab_name'=> $request->tab_name]);

            \Log::info('item_id : '.$item_id);

            $old_item = Item::find($item_id);

            if (isset($request->hidden_lifecycle_id) && $request->hidden_lifecycle_id === "13") {
                flash()->success(__('Lifecycle of :name has been updated', ['name' => Item::getNameById($item_id)]));
                return redirect(route('item.items.show_item', [$old_item, 'item_lifecycle' ]));
            }

            if($old_item->permission_to_sell != 'Y'){

                $payload = [
                    'valuer_id' => $request->valuer_id,
                    'vat_tax_rate' => $request->vat_tax_rate,
                    'low_estimate' => $request->low_estimate,
                    'high_estimate' => $request->high_estimate,
                    'is_reserve' => isset($request->is_reserve)?'Y':'N',
                    'reserve' => isset($request->reserve)?$request->reserve:null,
                    'is_hotlotz_own_stock' => isset($request->is_hotlotz_own_stock)?'Y':'N',
                    'supplier' => isset($request->supplier)?$request->supplier:null,
                    'purchase_cost' => isset($request->purchase_cost)?$request->purchase_cost:null,
                    'supplier_gst' => isset($request->supplier_gst)?$request->supplier_gst:null,
                ];

                if (isset($request->lifecycle_id)) {
                    $payload['lifecycle_id'] = $request->lifecycle_id;

                    if ($old_item->lifecycle_id > 0 && $old_item->lifecycle_id != $request->lifecycle_id) {
                        ItemLifecycle::where('item_id', $item_id)->forceDelete();
                        $del_lot_ids = AuctionItem::where('item_id', $item_id)->pluck('lot_id')->all();

                        foreach ($del_lot_ids as $del_lot_id) {
                            if ($del_lot_id != null) {
                                // event(new GAPDeleteLotEvent($del_lot_id));
                                \Log::channel('gapLog')->info('dispatch LotDeleteJob');
                                LotDeleteJob::dispatch($del_lot_id);
                            }
                        }
                        AuctionItem::where('item_id', $item_id)->forceDelete();
                    }
                }

                $result = $this->itemRepository->update($item_id, $payload, true, 'Lifecycle');

                $item = Item::find($item_id);
                $existing_ids = ItemLifecycle::where('item_id', $item_id)->pluck('id')->all();

                // dd($request->all());
                if (isset($request->type) && count($request->type) > 0) {
                    for ($i=0; $i < count($request->type); $i++) {
                        ##Start - added by mct[9May2022]
                        if($request->price[$i] < 80 && ($request->type[$i] == 'auction' || $request->type[$i] == 'marketplace' || $request->type[$i] == 'clearance') ){
                            DB::rollback();
                            flash()->error(__('Error::msg', ['msg' => $old_item->name.'\'s Lifecycle update Failed. Opening Bid, Marketplace/Clearance price must be greater than 80.']));
                            return redirect(route('item.items.show_item', [Item::find($item_id), 'item_lifecycle' ]));
                        }
                        ##End - added by mct[9May2022]


                        if ($request->type[$i] == 'auction') {
                            $reference_id = $request->auction_id[$i];

                            $exist_auction_count = AuctionItem::where('item_id', $item_id)->where('auction_id', $request->auction_id[$i])->count();

                            if ($exist_auction_count <= 0) {
                                $auction = Auction::find($request->auction_id[$i]);

                                $auction_item = [
                                    'auction_id' => $request->auction_id[$i],
                                    'item_id' => $item_id,
                                    'status' => null,
                                    'lot_id' => null,
                                    'lot_number' => null,
                                    'sequence_number' => null,
                                    'end_time_utc' => $auction->timed_first_lot_ends,
                                    'starting_bid' => $request->price[$i],
                                ];

                                $new_auction_item = AuctionItem::create($auction_item);

                                $lot_no_data['lot_number'] = $new_auction_item->id;
                                $lot_no_data['sequence_number'] = $new_auction_item->id;
                                AuctionItem::where('id',$new_auction_item->id)->update($lot_no_data);
                            }
                            if($exist_auction_count > 0){
                                AuctionItem::where('item_id', $item_id)->where('auction_id', $request->auction_id[$i])->update(['starting_bid'=>$request->price[$i]] + NHelpers::updated_at_by());
                            }
                        } else {
                            $reference_id = isset($request->hid_marketplace[$i])?$request->hid_marketplace[$i]:'';
                        }

                        $second_period = null;
                        $is_indefinite_period = null;
                        if ($request->type[$i] == 'storage') {
                            $second_period = $request->second_period;
                            $is_indefinite_period = isset($request->is_indefinite_period)?'Y':'N';
                        }

                        $item_lifecycle = [];
                        $item_lifecycle['item_id'] = $item_id;
                        $item_lifecycle['type'] = $request->type[$i];
                        $item_lifecycle['price'] = $request->price[$i];
                        $item_lifecycle['reference_id'] = $reference_id;
                        $item_lifecycle['period'] = $request->period[$i];
                        $item_lifecycle['second_period'] = $second_period;
                        $item_lifecycle['is_indefinite_period'] = $is_indefinite_period;


                        if (in_array($request->item_lifecycle_id[$i], $existing_ids) && $request->item_lifecycle_id[$i] != 0) {
                            ItemLifecycle::where('id', $request->item_lifecycle_id[$i])->update($item_lifecycle + NHelpers::updated_at_by());
                        } else {
                            ItemLifecycle::insert($item_lifecycle + NHelpers::created_updated_at_by());
                        }
                    }
                }

                $existing_il_auction_ids = ItemLifecycle::where('item_id', $item_id)->where('type', 'auction')->pluck('reference_id')->all();

                ## Delete Lot
                $delete_auctionitems = AuctionItem::where('item_id', $item_id)->whereNotIn('auction_id', $existing_il_auction_ids)->pluck('lot_id', 'auction_id')->all();

                foreach ($delete_auctionitems as $del_auction_id => $del_lot_id) {
                    if ($del_lot_id != null) {
                        // event(new GAPDeleteLotEvent($del_lot_id));
                        \Log::channel('gapLog')->info('dispatch LotDeleteJob');
                        LotDeleteJob::dispatch($del_lot_id);
                    }

                    AuctionItem::where('item_id', $item_id)->where('auction_id', $del_auction_id)->forceDelete();
                }

                flash()->success(__('Lifecycle of :name has been updated', ['name' => Item::getNameById($item_id)]));

                DB::commit();
                return redirect(route('item.items.show_item', [Item::find($item_id), 'item_lifecycle' ]));
            }else{
                DB::rollback();
                flash()->error(__('Error::msg', ['msg' => $old_item->name.'\'s Lifecycle update Failed. This item has already permission to sell.']));
                return redirect(route('item.items.show_item', [Item::find($item_id), 'item_lifecycle' ]));
            }
        } catch (Exception $e) {
            DB::rollback();
            flash()->error(__('Error::msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    public function getCategoryProperty(Request $request)
    {
        try {
            $categoryproperties = CategoryProperty::where('category_id', $request->category_id)->where('key', '!=', 'Sub Category')->select('id', 'key', 'value', 'field_type', 'is_required', 'is_filter')->get();
            // dd($categoryproperties->toArray());

            $item = [];
            if ($request->item_id) {
                $item = Item::where('id', $request->item_id)->where('category_id', $request->category_id)->first();
            }

            $returnHTML = view('item::itemdetails._category_property', [
                'categoryproperties' => $categoryproperties,
                'item' => $item,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Get Category Properties Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function saveItemFeeStructure($item_id, Request $request)
    {
        DB::beginTransaction();
        try {
            session()->put('tab_name', $request->tab_name);

            \Log::info('item_id : '.$item_id);

            $old_item = Item::find($item_id);

            if($old_item->permission_to_sell != 'Y'){
                $payload = [
                    'fee_type' => $request->fee_type,
                    'is_fee_structure_needed' => 'N',
                    'internal_notes' => ($request->sales_commission != '20')?'Seller’s Commission is NOT 20%':null,
                ];

                $result = $this->itemRepository->update($item_id, $payload, true, 'FeeStructure');

                // $item_fees = $this->itemRepository->getItemFees($request);
                $item_fee_structure = [];
                if ($request->fee_type == 'sales_commission') {
                    $item_fee_structure = [
                        'item_id' => $item_id,
                        'fee_type' => $request->fee_type,
                        'sales_commission' => $request->sales_commission,
                        'fixed_cost_sales_fee' => null,
                        'hotlotz_owned_stock' => null,
                        'performance_commission_setting' => $request->performance_commission_setting,
                        'performance_commission' => $request->performance_commission,
                        'minimum_commission_setting' => $request->minimum_commission_setting,
                        'minimum_commission' => $request->minimum_commission,
                        'insurance_fee_setting' => $request->insurance_fee_setting,
                        'insurance_fee' => $request->insurance_fee,
                        'listing_fee_setting' => $request->listing_fee_setting,
                        'listing_fee' => $request->listing_fee,
                        'unsold_fee_setting' => $request->unsold_fee_setting,
                        'unsold_fee' => $request->unsold_fee,
                        'withdrawal_fee_setting' => $request->withdrawal_fee_setting,
                        'withdrawal_fee' => $request->withdrawal_fee,
                        'ic_details' => $request->ic_details,
                        'ic_amount' => $request->ic_amount,
                        'ic_commissioner' => $request->ic_commissioner,
                    ];
                }
                if ($request->fee_type == 'fixed_cost_sales_fee') {
                    $item_fee_structure = [
                        'item_id' => $item_id,
                        'fee_type' => $request->fee_type,
                        'sales_commission' => null,
                        'fixed_cost_sales_fee' => $request->fixed_cost_sales_fee,
                        'hotlotz_owned_stock' => null,
                        'performance_commission_setting' => null,
                        'performance_commission' => null,
                        'minimum_commission_setting' => null,
                        'minimum_commission' => null,
                        'insurance_fee_setting' => $request->insurance_fee_setting,
                        'insurance_fee' => $request->insurance_fee,
                        'listing_fee_setting' => $request->listing_fee_setting,
                        'listing_fee' => $request->listing_fee,
                        'unsold_fee_setting' => $request->unsold_fee_setting,
                        'unsold_fee' => $request->unsold_fee,
                        'withdrawal_fee_setting' => $request->withdrawal_fee_setting,
                        'withdrawal_fee' => $request->withdrawal_fee,
                        'ic_details' => $request->ic_details,
                        'ic_amount' => $request->ic_amount,
                        'ic_commissioner' => $request->ic_commissioner,
                    ];
                }
                if ($request->fee_type == 'hotlotz_owned_stock') {
                    $item_fee_structure = [
                        'item_id' => $item_id,
                        'fee_type' => $request->fee_type,
                        'sales_commission' => null,
                        'fixed_cost_sales_fee' => null,
                        'hotlotz_owned_stock' => isset($request->hotlotz_owned_stock)?$request->hotlotz_owned_stock:null,
                        'performance_commission_setting' => null,
                        'performance_commission' => null,
                        'minimum_commission_setting' => null,
                        'minimum_commission' => null,
                        'insurance_fee_setting' => null,
                        'insurance_fee' => null,
                        'listing_fee_setting' => null,
                        'listing_fee' => null,
                        'unsold_fee_setting' => null,
                        'unsold_fee' => null,
                        'withdrawal_fee_setting' => null,
                        'withdrawal_fee' => null,
                        'ic_details' => null,
                        'ic_amount' => null,
                        'ic_commissioner' => null,
                    ];
                }

                if (count($item_fee_structure) > 0) {
                    if ($request->item_fee_structure_id != 0) {
                        ItemFeeStructure::where('id', $request->item_fee_structure_id)->update($item_fee_structure + NHelpers::updated_at_by());
                    } else {
                        ItemFeeStructure::insert($item_fee_structure + NHelpers::created_updated_at_by());
                    }
                }

                DB::commit();
                flash()->success(__('Fee Structure of :name has been updated', ['name' => Item::getNameById($item_id)]));
                return redirect(route('item.items.show_item', [Item::find($item_id), 'fee_structure' ]));

            }else{
                DB::rollback();
                flash()->error(__('Error::msg', ['msg' => $old_item->name.'\'s Fee Structure update Failed. This item has already permission to sell.']));
                return redirect(route('item.items.show_item', [Item::find($item_id), 'fee_structure' ]));
            }

        } catch (Exception $e) {
            DB::rollback();
            flash()->error(__('Error::msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    public function itemPurchaseDetails($item_id, Request $request)
    {
        //
    }

    public function duplicateItem($item_id)
    {
        DB::beginTransaction();
        try {
            $item = Item::find($item_id);
            $payload = $this->itemRepository->getPayloadForDuplicateItem($item);

            $new_item = $this->itemRepository->create($payload);
            $this->itemRepository->cloneImage($item_id, $new_item->id);
            $this->itemRepository->cloneLifecycle($item_id, $new_item->id);
            $this->itemRepository->cloneFeeStructure($item_id, $new_item->id);

            DB::commit();

            event(new ItemCreatedEvent($new_item));

            return response()->json(array('status'=>'success','message'=>'Duplicated item '.$new_item->name.' has been created'));
        } catch (\Exception $e) {
            DB::rollback();
            // flash()->error(__('Error::msg', ['msg' => $e->getMessage()]));
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function generateItemCode($customer_id)
    {
        try {
            if ($customer_id) {
                $item_code_data = Item::generateItemCode($customer_id);

                return response()->json(array('status'=>'success','message'=>'Generate Item Code successfully!','item_code_data'=>$item_code_data));
            }

            return response()->json(array('status'=>'failed','message'=>'Generate Item Code failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function approvedCataloguing($item_id, Request $request)
    {
        try {
            if ($item_id) {
                $payload = [
                    'cataloguing_approver_id'=>$request->cataloguing_approver_id,
                    'is_cataloguing_approved'=>'Y',
                    'cataloguing_approval_date' => date('Y-m-d H:i:s'),
                    'cataloguing_needed' => 'N',
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'ApprovedCataloguing');


                $item = Item::find($item_id);

                // if ($item->is_cataloguing_approved == 'Y' && $item->is_valuation_approved == 'Y' && $item->is_fee_structure_approved == 'Y' && $item->is_hotlotz_own_stock == 'Y') {
                //     $this->itemRepository->update($item_id, ['permission_to_sell'=>'Y','seller_agreement_signed_date'=>date('Y-m-d H:i:s')], true, 'PermissionToSell');
                // }

                return response()->json(array('status'=>'success','message'=>'Approved Item Cataloguing Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Approved Item Cataloguing Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function approvedValuation($item_id, Request $request)
    {
        try {
            if ($item_id) {
                ##Start - Added by mct [15June22]
                $item = Item::find($item_id);
                if($item && ($item->low_estimate == 12345 || $item->high_estimate == 12345 || $item->reserve == 12345)){
                    return response()->json(array('status'=>'failed','message'=>'Default valuation number is filled. Please check and change your valuation before approval.'));
                }

                foreach ($item->itemlifecycles as $key => $item_lifecycle) {
                    if($item_lifecycle->price == 12345){
                        return response()->json(array('status'=>'failed','message'=>'Default valuation number is filled. Please check and change your valuation before approval.'));
                    }
                }
                ##End - Added by mct [15June22]

                $payload = [
                    'valuation_approver_id'=>$request->valuation_approver_id,
                    'is_valuation_approved'=>'Y',
                    'valuation_approval_date' => date('Y-m-d H:i:s')
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'ApprovedValuation');


                $item = Item::find($item_id);

                if ($item->is_valuation_approved == 'Y' && $item->is_fee_structure_approved == 'Y' && $item->is_hotlotz_own_stock == 'Y') {
                    $this->itemRepository->update($item_id, ['permission_to_sell'=>'Y','seller_agreement_signed_date'=>date('Y-m-d H:i:s')], true, 'PermissionToSell');
                }

                return response()->json(array('status'=>'success','message'=>'Approved Item Valuation Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Approved Item Valuation Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function approvedFeeStructure($item_id, Request $request)
    {
        try {
            if ($item_id) {
                $payload = [
                    'fee_structure_approver_id'=>$request->fee_structure_approver_id,
                    'is_fee_structure_approved'=>'Y',
                    'fee_structure_approval_date' => date('Y-m-d H:i:s')
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'ApprovedFeeStructure');


                $item = Item::find($item_id);

                if ($item->is_valuation_approved == 'Y' && $item->is_fee_structure_approved == 'Y' && $item->is_hotlotz_own_stock == 'Y') {
                    $this->itemRepository->update($item_id, ['permission_to_sell'=>'Y','seller_agreement_signed_date'=>date('Y-m-d H:i:s')], true, 'PermissionToSell');
                }

                return response()->json(array('status'=>'success','message'=>'Approved Item Fee Structure Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Approved Item Fee Structure Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function declinedItem($item_id)
    {
        try {
            if ($item_id) {
                event(new DeclinedItemEvent($item_id));

                return response()->json(array('status'=>'success','message'=>'Declined Item Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Declined Item Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function withdrawnItem($item_id)
    {
        try {
            if ($item_id) {
                event(new WithdrawItemEvent($item_id));
                return response()->json(array('status'=>'success','message'=>'Withdrawn Item Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Withdrawn Item Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function internalWithdrawnItem($item_id)
    {
        try {
            $item = Item::find($item_id);
            if ($item) {
                $payload = [];
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
                $payload['seller_agreement_signed_date'] = null;
                $payload['saleroom_receipt_date'] = null;
                $payload['entered_auction1_date'] = null;
                $payload['entered_auction2_date'] = null;
                $payload['entered_marketplace_date'] = null;
                $payload['entered_clearance_date'] = null;
                $payload['internal_withdraw_date'] = date('Y-m-d H:i:s');
                #Clear Tag
                $payload['tag'] = null;

                $result = $this->itemRepository->update($item_id, $payload, true, 'InternalWithdrawn');

                ItemLifecycle::where('item_id', $item_id)->forceDelete();
                AuctionItem::where('item_id', $item_id)->forceDelete();

                //for Item History
                $item_history = [
                    'item_id' => $item_id,
                    'customer_id' => $item->customer_id,
                    'auction_id' => null,
                    'item_lifecycle_id' => null,
                    'price' => null,
                    'type' => 'internalwithdraw',
                    'status' => 'Internal Withdrawn',
                    'entered_date' => date('Y-m-d H:i:s'),
                ];
                \Log::info('call ItemHistoryEvent - Internal Withdrawn');
                event( new ItemHistoryEvent($item_history) );

                return response()->json(array('status'=>'success','message'=>'Internal Withdrawn Item Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Internal Withdrawn Item Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function dispatchedItem($item_id, Request $request)
    {
        try {
            if ($item_id) {
                $dispatched_or_collected_date = date("Y-m-d H:i:s", strtotime($request->dispatched_or_collected_date));

                $payload = [
                    // 'status'=>Item::_DISPATCHED_,
                    'dispatched_or_collected_date' => $dispatched_or_collected_date,
                    'dispatched_person' => $request->dispatched_person,
                    'dispatched_remark' => $request->dispatched_remark,
                    'tag' => 'dispatched',
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'Dispatched');

                $itemlifecycle_data = [
                    'action'=>ItemLifecycle::_FINISHED_,
                ];
                ItemLifecycle::where('item_id', $item_id)->where('type', 'storage')->update($itemlifecycle_data + NHelpers::updated_at_by());

                event(new OrderWasCompleted(Item::find($item_id)));

                return response()->json(array('status'=>'success','message'=>'Dispatched Item Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Dispatched Item Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function cancelDispatchItem($item_id)
    {
        DB::beginTransaction();
        try {
            $item = Item::find($item_id);
            if ($item) {
                $current_date = date('Y-m-d H:i:s');

                $payload = [
                    'cancel_dispatch_date' => $current_date,
                    'tag' => 'in_storage',
                    // 'dispatched_or_collected_date' => null,
                    // 'dispatched_person' => null,
                    // 'dispatched_remark' => null,
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'Dispatched');

                $itemlifecycle_data = [
                    'action'=>ItemLifecycle::_PROCESSING_,
                ];
                ItemLifecycle::where('item_id', $item_id)->where('type', 'storage')->update($itemlifecycle_data + NHelpers::updated_at_by());

                //for Item History
                $item_history = [
                    'item_id' => $item_id,
                    'customer_id' => $item->customer_id,
                    'auction_id' => null,
                    'item_lifecycle_id' => null,
                    'price' => null,
                    'type' => 'canceldispatch',
                    'status' => 'Cancel Dispatch',
                    'entered_date' => $current_date,
                ];
                \Log::info('call ItemHistoryEvent - Cancel Dispatch');
                event( new ItemHistoryEvent($item_history) );

                DB::commit();                
                return response()->json(array('status'=>'success','message'=>'Cancel Dispatched Item Successfully!'));
            }

            DB::rollback();
            return response()->json(array('status'=>'failed','message'=>'Cancel Dispatched Item Failed!'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function deliveryBookedItem($item_id, Request $request)
    {
        try {
            if ($item_id) {
                $delivery_booked_date = date("Y-m-d H:i:s", strtotime($request->delivery_booked_date));

                $payload = [
                    'delivery_booked' => 'Y',
                    'delivery_booked_date' => $delivery_booked_date,
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'DeliveryBooked');

                $itemlifecycle_data = [
                    'action'=>ItemLifecycle::_FINISHED_,
                ];
                ItemLifecycle::where('item_id', $item_id)->where('type', 'storage')->update($itemlifecycle_data + NHelpers::updated_at_by());

                return response()->json(array('status'=>'success','message'=>'Delivery Booked Item Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Delivery Booked Item Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function cancelSaleItem($item_id, Request $request)
    {
        DB::beginTransaction();
        try {
            if ($item_id) {

                $item = Item::find($item_id);
                $payload = $this->itemRepository->getCancelSaleItemData();

                $result = $this->itemRepository->update($item_id, $payload, true, 'CancelSale');

                $sold_auction_item = AuctionItem::where('item_id', $item_id)->where('status',Item::_SOLD_)->first();

                ItemLifecycle::where('item_id', $item_id)->forceDelete();
                AuctionItem::where('item_id', $item_id)->forceDelete();

                //for Item History
                $item_history = [
                    'item_id' => $item_id,
                    'customer_id' => $item->customer_id,
                    'auction_id' => null,
                    'item_lifecycle_id' => null,
                    'price' => null,
                    'type' => 'cancelsale',
                    'status' => 'Cancel Sale',
                    'entered_date' => date('Y-m-d H:i:s'),
                ];
                \Log::info('call ItemHistoryEvent - Cancel Sale');
                event( new ItemHistoryEvent($item_history) );

                DB::commit();

                ### Invoice Cancel into Xero
                if(isset($sold_auction_item)){
                    $xero_payload['item_id'] = $item_id;
                    $xero_payload['auction_id'] = $sold_auction_item->auction_id;
                    $xero_payload['private_sale_buyer_premium'] = $item->private_sale_buyer_premium;
                }else{
                    $xero_payload['item_id'] = $item_id;
                    $xero_payload['auction_id'] = null;
                    $xero_payload['private_sale_buyer_premium'] = $item->private_sale_buyer_premium;
                }
                event( new XeroInvoiceCancelEvent($xero_payload, 'cancel') );

                // #CancelSale Email Send
                // event(new CancelSaleItemEvent($item_id));

                return response()->json(array('status'=>'success','message'=>'Cancel Sale Item Successfully!'));
            }

            DB::rollback();
            return response()->json(array('status'=>'failed','message'=>'Cancel Sale Item Failed!'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function creditNoteItem($item_id, Request $request)
    {
        DB::beginTransaction();
        try {
            if ($item_id) {
                $item = Item::find($item_id);
                $payload = $this->itemRepository->getPayloadForDuplicateItem($item, 'CreditNote');
                $sold_auction_item = AuctionItem::where('item_id', $item_id)->where('status', Item::_SOLD_)->first();

                   ### Invoice Cancel into Xero
                if (isset($sold_auction_item)) {
                    $xero_payload['item_id'] = $item_id;
                    $xero_payload['auction_id'] = $sold_auction_item->auction_id;
                    $xero_payload['private_sale_buyer_premium'] = $item->private_sale_buyer_premium;
                } else {
                    $xero_payload['item_id'] = $item_id;
                    $xero_payload['auction_id'] = null;
                    $xero_payload['private_sale_buyer_premium'] = $item->private_sale_buyer_premium;
                }

                $new_item = $this->itemRepository->create($payload);
                if ($new_item != null) {
                    \Log::info('Credit Note item_id : '.$new_item->id);
                    $this->itemRepository->cloneImage($item_id, $new_item->id, 'CreditNote');
                    $this->itemRepository->cloneLifecycle($item_id, $new_item->id);
                    $this->itemRepository->cloneFeeStructure($item_id, $new_item->id);
                    event(new ItemCreatedEvent($new_item));

                    $old_item_data = [
                        'status' => Item::_ITEM_RETURNED_,
                        'is_credit_noted' => 'Y',
                        'is_credit_note_item' => null,//no need for original sold item
                        'credit_note_date' => date('Y-m-d H:i:s'),
                    ];
                    $this->itemRepository->update($item_id, $old_item_data, true, 'CancelSale');


                    //for Item History
                    $item_history = [
                        'item_id' => $item_id,
                        'customer_id' => $item->customer_id,
                        'auction_id' => null,
                        'item_lifecycle_id' => null,
                        'price' => null,
                        'type' => 'credit_note',
                        'status' => Item::_ITEM_RETURNED_,
                        'entered_date' => date('Y-m-d H:i:s'),
                    ];
                    \Log::info('call ItemHistoryEvent - Credit Note');
                    event( new ItemHistoryEvent($item_history) );

                    event(new XeroInvoiceCancelEvent($xero_payload, 'credit'));

                    DB::commit();
                    return response()->json(array('status'=>'success','message'=>'Credit Note Item Successfully!'));
                }
            }

            DB::rollback();
            return response()->json(array('status'=>'failed','message'=>'Credit Note Item Failed!'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function privateSaleItem($item_id, Request $request)
    {
        try {
            if ($item_id) {
                $today = date('Y-m-d H:i:s');
                $sold_price_inclusive_gst = $request->price;
                $sold_price_exclusive_gst = $request->price / 1.08;

                $payload = [
                    'status'=>Item::_SOLD_,
                    'lifecycle_status'=>Item::_PRIVATE_SALE_,
                    'private_sale_type' => $request->private_sale_type,
                    'private_sale_auction_id' => ($request->private_sale_type == 'auction')? $request->auction_id:null,
                    'private_sale_price' => $request->price,
                    'private_sale_buyer_premium' => $request->buyer_premium,
                    'private_sale_date' => $today,
                    'private_sale_date' => $today,
                    'sold_price' => $request->price,
                    'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
                    'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
                    'sold_date' => $today,
                    'sold_date' => $today,
                    'buyer_id' => $request->buyer_id,
                    'storage_date' => $today,
                    'tag' => 'in_storage',
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'PrivateSale');

                //for Item History
                $item = Item::find($item_id);
                $item_history = [
                    'item_id' => $item_id,
                    'auction_id' => null,
                    'customer_id' => $item->customer_id ?? null,
                    'buyer_id' => $request->buyer_id,
                    'item_lifecycle_id' => null,
                    'price' => null,
                    'sold_price' => $request->price,
                    'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
                    'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
                    'type' => 'privatesale',
                    'status' => Item::_PRIVATE_SALE_,
                    'entered_date' => $today,
                ];
                \Log::info('call ItemHistoryEvent - PrivateSale');
                event( new ItemHistoryEvent($item_history) );

                ## Xero Invoice for Private Sale
                $payload['buyer_id'] = $request->buyer_id;
                $payload['item_id'] = $item_id;
                $payload['price'] = $request->price;
                $payload['sold_price_inclusive_gst'] = $sold_price_inclusive_gst;
                $payload['sold_price_exclusive_gst'] = $sold_price_exclusive_gst;
                $payload['buyer_premiun'] = null;
                $payload['type'] = $request->private_sale_type;
                if($request->buyer_premium != '' || $request->buyer_premium != null){
                    $payload['buyer_premiun'] = $request->buyer_premium;
                }
                event( new XeroPrivateSaleInvoiceEvent($payload) );


                flash()->success(__('Private Sale of :name Successfully', ['name' => Item::getNameById($item_id)]));
                return redirect(route('item.items.show_item', [Item::find($item_id), 'item_lifecycle' ]));
            }

            flash()->error(__('Error: :msg', ['msg' => 'Private Sale Item Failed!']));
            return redirect()->route('item.items.show_item', [Item::find($item_id), 'item_lifecycle' ]);
        } catch (Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->route('item.items.show_item', [Item::find($item_id), 'item_lifecycle' ]);
        }
    }

    public function requestForPermission($item_id)
    {
        $item = Item::find($item_id);
        if (isset($item)) {

            event( new SellerAgreementEvent($item->customer_id) );

            // $payload = [
            //     'consignment_flag'=>'Y'
            // ];
            // $result = $this->itemRepository->update($item_id, $payload, true, 'ConsignmentEmail');

            return redirect(route('item.items.show_item', [$item, 'overview' ]));
        }
    }

    public function checkTab(Request $request)
    {
        session(['tab_name'=> $request->tab_name]);

        return response()->json(array('status'=>1,'message'=>'Check Tab Successfully!'));
    }

    public function setHighlight($item_id, Request $request)
    {
        try {
            if ($item_id) {
                $is_highlight = $request->is_highlight;
                $payload = [
                    'is_highlight'=>$is_highlight,
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'Highlight');

                return response()->json(array('status'=>'success','message'=>'Set Highlight Item Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Set Highlight Item Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function search()
    {
        $items = Item::query()->where('name', 'LIKE', '%'.request('name') .'%')->orWhere('item_number', 'LIKE', '%'.request('name') .'%')->get();
        $names = [];

        foreach($items as $item){
            $names[] = $item->search_name;
        }

        return json_encode($names);
    }

    public function getRecentlyConsigned(Request $request)
    {
        try{
            event(new RecentlyConsignedEvent($request->id, $request->status));
            return response()->json(array('status'=>'success','message'=>'Set Recently Consigned Item Successfully!'));
        }catch (Exception $e){
            return response()->json(array('status'=>'error','message'=>'Internal server error!'));
        }
    }

    public function setPendingStatus($item_id)
    {
        try {
            if ($item_id) {
                $payload = [
                    'status'=>Item::_PENDING_,
                ];
                $result = $this->itemRepository->update($item_id, $payload, true, 'setPendingStatus');

                return response()->json(array('status'=>'success','message'=>'Set Pending Status Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Set Pending Status Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function getimagefullpath($photoID)
    {
        $photo = ItemImage::find($photoID);
        return response()->json(['status' => 'success', 'path' => $photo->full_path]);
    }

    public function getConditions(Request $request)
    {
        try {
            $conditions = Item::getConditionList($request->category_id);

            return response()->json(array('status' => 'success','message'=>'Get Conditions Successfully.', 'data'=>$conditions));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function getConditionSolution(Request $request)
    {
        try {
            $condition_solution = Item::getConditionSolution($request->condition);

            return response()->json(array('status' => 'success','message'=>'Get Condition Solution Successfully.', 'condition_solution'=>$condition_solution));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }
    
    public function withdrawFeeSetting(Item $item, Request $request)
    {
        try {
            $item->fee_structure->withdrawal_fee_setting = $request->isChecked == 'false' ? 0 : 1;
            $item->fee_structure->save();
            return response()->json(array('status'=>'success','message'=>'Set Withdrawl Fee Setting Successfully!'));

        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }

    }
}
