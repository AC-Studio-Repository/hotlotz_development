<?php

namespace App\Http\Controllers\Marketplace;

use DB;
use Auth;
use Hash;
use View;
use Carbon\Carbon;
use App\Helpers\MenuHelper;
use Illuminate\Http\Request;
use App\Modules\Item\Models\Item;
use App\Repositories\CMSRepository;
use App\Http\Controllers\Controller;
use App\Repositories\ItemRepository;
use App\Repositories\BannerRepository;
use App\Repositories\AuctionRepository;
use App\Repositories\ContactRepository;
use App\Repositories\CountryRepository;
use App\Repositories\ProfileRepository;
use App\Modules\Item\Models\ItemLifecycle;
use App\Repositories\FavouritesRepository;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\MarketplaceRepository;
use App\Modules\Category\Models\CategoryProperty;
use App\Modules\Category\Models\Category;

class MarketplaceController extends Controller
{
    protected $itemRepository;
    protected $bannerRepository;
    protected $auctionRepository;
    protected $marketplaceRepository;
    protected $contactRepository;
    protected $cmsRepository;
    protected $profileRepository;
    protected $favouritesRepository;
    protected $countryRepository;

    public function __construct(
        ItemRepository $itemRepository,
        BannerRepository $bannerRepository,
        AuctionRepository $auctionRepository,
        MarketplaceRepository $marketplaceRepository,
        ContactRepository $contactRepository,
        CMSRepository $cmsRepository,
        ProfileRepository $profileRepository,
        FavouritesRepository $favouritesRepository,
        CountryRepository $countryRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->bannerRepository = $bannerRepository;
        $this->auctionRepository = $auctionRepository;
        $this->marketplaceRepository = $marketplaceRepository;
        $this->contactRepository = $contactRepository;
        $this->cmsRepository = $cmsRepository;
        $this->profileRepository = $profileRepository;
        $this->favouritesRepository = $favouritesRepository;
        $this->countryRepository = $countryRepository;
    }

    public function home()
    {
        $title = 'Marketplace';
        $marketplace_main_banner = $this->bannerRepository->getMarketplaceHomeBanners();
        $hotlotzPick = $this->itemRepository->getHotlotzPick();
        $collabrationBanners = $this->bannerRepository->getCollaborationBanner();
        $categories = $this->marketplaceRepository->getCategories();
        $sustainableSourcingBanners = $this->bannerRepository->getMarketplaceSustainableSourcingBanners();
        $marketplaceHightlightTitle = 'Discover More';
        $marketplaceHighlights = $this->itemRepository->getMarketplaceHighlights();
        $today_open_time = SysConfig::getTodayOpenTime();

        $data = [
            'title' => $title,
            'marketplace_main_banners' => $marketplace_main_banner,
            'hotlotzPicks' => $hotlotzPick,
            'collabrationBanners' => $collabrationBanners,
            'categories' => $categories,
            'sustainableSourcingBanners' => $sustainableSourcingBanners,
            'marketplaceHightlightTitle' => $marketplaceHightlightTitle,
            'marketplaceHighlights' => $marketplaceHighlights,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'today_open_time' => $today_open_time,
        ];

        return view('pages.marketplace.home', $data);
        // return view('pages.marketplace.home_temp', $data);
    }

    public function categoryDetail($id)
    {
        #//Start - command out by mct[5May22]
        // $title = '';
        // if ($id == 'maps-bonds') {
        //     $title = "Collectibles";
        //     $category_id = 1;
        // }
        // if ($id == 'designer-fashion') {
        //     $title = "Designer Fashion";
        //     $category_id = 4;
        // }
        // if ($id == 'home-decor') {
        //     $title = "Home Decor";
        //     $category_id = 7;
        // }
        // if ($id == 'tableware') {
        //     $title = "Antique & Fine";
        //     $category_id = 9;
        // }
        // if ($id == 'new-arrival') {
        //     $title = "New Arrivals";
        //     $category_id = 'new-arrival';
        // }
        // if ($id == 'asian-collectables') {
        //     $title = "Asian Works of Art";
        //     $category_id = 3;
        // }
        // if ($id == 'art') {
        //     $title = "Art";
        //     $category_id = 2;
        // }
        // if ($id == 'jewellery') {
        //     $title = "Jewellery";
        //     $category_id = 6;
        // }
        // if ($id == 'watches') {
        //     $title = "Watches";
        //     $category_id = 10;
        // }
        // if ($id == 'decorative-arts') {
        //     $title = "Decorative Arts";
        //     $category_id = 12;
        // }
        // if ($id == 'furniture') {
        //     $title = "Furniture";
        //     $category_id = 5;
        // }
        // if ($id == 'rugs-carpets') {
        //     $title = "Rugs & Carpets";
        //     $category_id = 8;
        // }
        // if ($id == 'wine-spirits') {
        //     $title = "Wine & Spirits";
        //     $category_id = 11;
        // }
        // if ($id == 'clearance') {
        //     $title = "Clearance";
        //     $category_id = 'clearance';
        // } 
        #//End - command out by mct [5May22]

        #Start - added by mct [5May22]
        $title = '';
        $category_id = $id;
        if ($id != 'new-arrival' && $id != 'clearance') {
            $category = Category::find($id);
            $title = ($category)?$category->name:'';
        }
        if ($id == 'new-arrival') {
            $title = "New Arrivals";
            $category_id = 'new-arrival';
        }
        if ($id == 'clearance') {
            $title = "Clearance";
            $category_id = 'clearance';
        }
        #End - added by mct [5May22]

        $offset = 0;
        $limit = 16;

        $menu = MenuHelper::getMenuMarketplace();
        $today_open_time = SysConfig::getTodayOpenTime();

        $type = request()->item_type;
        $price = request()->price;
        $search_query = request()->search;
        $sub_category = request()->sub_category;

        if ($id == 'new-arrival') {
            $items = $this->itemRepository->getItemsNewArrival($offset, $limit, $search_query, $type, $price, $sub_category);
        } elseif ($id == 'clearance') {
            $items = $this->itemRepository->getItemsClearance($offset, $limit, $search_query, $type, $price, $sub_category);
        } else {
            $items = $this->itemRepository->getItemsByCategory($category_id, $offset, $limit, $search_query, $type, $price, $sub_category);
        }

        $sub_category = CategoryProperty::where('category_id', $category_id)->where('key', 'Sub Category')->pluck('value')->first();
        $subcategories = [];
        if (isset($sub_category)) {
            $sub_category = explode(',', $sub_category);

            foreach ($sub_category as $key => $value) {
                $subcategories[$value] = $value;
            }
        }

        $pagination = 16;

        if(request()->per_page){
            $pagination = (request()->per_page == 'all') ? $items->count() : request()->per_page    ;
        }

        $data = [
            'title' => $title,
            'menus' => $menu,
            'today_open_time' => $today_open_time,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'items' => $items->paginate((int) $pagination)->appends(request()->except('page')),
            'category_id' => $category_id,
            'subcategories' => $subcategories,
            'id' => $id
        ];

        return view('pages.marketplace.category', $data);
    }

    public function getCategoryPagerAjax(Request $request)
    {
        $offset = $request->offset;
        $limit = $request->limit;
        $category_id = $request->category_id;
        $item_total_count = $request->item_total_count;
        $per_page = $request->per_page;

        $items = [];
        $item_total_count = $item_total_count;
        $show_pages = 0;
        $current_page = $request->current_page;

        if ($category_id == 'new-arrival') {
             $item_total_count = $this->itemRepository->getgetItemsNewArrivalTotalCount();
            $items = $this->itemRepository->getItemsNewArrival($offset, $limit);
        } elseif ($category_id == 'clearance') {
            $item_total_count = $this->itemRepository->getItemsClearanceTotalCount();
            $items = $this->itemRepository->getItemsClearance($offset, $limit);
        } else {
            $item_total_count = $this->itemRepository->getItemTotalCountByCategory($category_id);
            $items = $this->itemRepository->getItemsByCategory($category_id, $offset, $limit);
        }

        $show_pages = ceil($item_total_count/$per_page);
        $mod_items = $item_total_count % $per_page;

        $data = [
            'items' => $items,
            'item_total_count' => $item_total_count,
            'show_pages' => $show_pages,
            'mod_items' => $mod_items,
            'current_page' => $current_page,
            'category_id' => $category_id,
            'item_total_count' => $item_total_count,
            'per_page' => $per_page
        ];

        return View::make("pages.marketplace.categoryAjax")->with($data)->render();
    }

    public function collaborations()
    {
        $title = 'Collaborations';
        $menu = MenuHelper::getMenuMarketplace();

        $banner_info = $this->bannerRepository->getCollabrationPageBanner();
        $cmsBlogs = $this->cmsRepository->getCollabrationBlog();

        // $items = $this->itemRepository->getItemsClearance();
        $items = $this->itemRepository->getCollaborationItems();

        $today_open_time = SysConfig::getTodayOpenTime();
        $data = [
            'title' => $title,
            'menus' => $menu,
            'banner' => $banner_info['image'],
            'caption' => $banner_info['caption'],
            'items' => $items,
            'today_open_time' => $today_open_time,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'cmsBlogs' => $cmsBlogs
        ];
        return view('pages.marketplace.collaborations', $data);
    }

    public function getAllMarketplaceItems()
    {
        $title = 'All Items';
        $menu = MenuHelper::getMenuMarketplace();

        $banner_info = $this->bannerRepository->getCollabrationPageBanner();
        $cmsBlogs = $this->cmsRepository->getCollabrationBlog();
        $today_open_time = SysConfig::getTodayOpenTime();

        $items = $this->itemRepository->getAllMarketplaceItems();
        $pagination = 16;
        if(request()->per_page){
            $pagination = (request()->per_page == 'all') ? $items->count() : request()->per_page    ;
        }

        $data = [
            'title' => $title,
            'menus' => $menu,
            'banner' => $banner_info['image'],
            'caption' => $banner_info['caption'],
            'items' => $items->paginate((int) $pagination)->appends(request()->except('page')),
            'today_open_time' => $today_open_time,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'cmsBlogs' => $cmsBlogs
        ];
        return view('pages.marketplace.all_items', $data);
    }

    public function itemDetail($id)
    {
        $favourite = 'no';
        $itemDetail = $this->itemRepository->getItemDetail($id);
        if ($itemDetail == null || $itemDetail->status != Item::_IN_MARKETPLACE_) {
            return abort(404);
        }
        $itemImages = $this->itemRepository->getItemImages($id);
        $itemVideos = $this->itemRepository->getItemVideos($id);
        $policy_cms = $this->cmsRepository->getItemDetailPolicy();

        $title = $itemDetail->name;
        $menus = MenuHelper::getMenuMarketplace();
        $today_open_time = SysConfig::getTodayOpenTime();
        $marketPlaces = $this->itemRepository->getMarketplaceItems($id);

        $category_properties = $this->getItemCategoryData($itemDetail->category_data, $itemDetail);

        $fist_slider_image = '';
        if (count($itemImages) > 0) {
            $fist_slider_image = $itemImages[0]['photoPath'];
        }

        if(Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
            $favourite_count = $this->favouritesRepository->getFavouriteItem($customer_id, $itemDetail->id);
            if($favourite_count > 0)
            {
                $favourite = 'yes';
            }else{
                $favourite = 'no';
            }
        }
        $display_price = number_format($itemDetail->price);

        $data = [
            'title' => $title,
            'menus' => $menus,
            'today_open_time' => $today_open_time,
            'item_data' => $itemDetail,
            'item_images' => $itemImages,
            'item_videos' => $itemVideos,
            'marketPlaceItems' => $marketPlaces,
            'fist_slider_image' => $fist_slider_image,
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'category_properties' => $category_properties,
            'policy_cms' => $policy_cms,
            'display_price' => $display_price,
            'favourite' => $favourite
        ];

        return view('pages.marketplace.item_detail', $data);
    }

    public function itemCheckout()
    {
        $title = "Shopping Bag";
        $menus = MenuHelper::getMenuMarketplace();
        $cardLists = $this->cardLists();
        $today_open_time = SysConfig::getTodayOpenTime();

        $data = [
            'title' => $title,
            'menus' => $menus,
            'today_open_time' => $today_open_time,
            'cardLists' => $cardLists,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
        ];

        return view('pages.item-checkout', $data);
    }

    public function itemCheckoutInfo()
    {
        $title = "Checkout";
        $menus = MenuHelper::getMenuMarketplace();
        $today_open_time = SysConfig::getTodayOpenTime();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');
        $customer_id = Auth::guard('customer')->user()->id;
        $address_count = 0;
        $addresses = $this->profileRepository->getCutomerAddress($customer_id);
        $stripeCountries = $this->countryRepository->getAllCountriesForStripe();

        if (!empty($addresses)) {
            $address_count = $addresses->count();
        }
        $data = [
            'today_open_time' => $today_open_time,
            'title' => $title,
            'menus' => $menus,
            'countries' => $countries,
            'addresses' => $addresses,
            'address_count' => $address_count,
            'stripeCountries' => $stripeCountries,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
        ];
        return view('pages.checkout-info', $data);
    }

    public function itemCheckoutFinal()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $data = [
            'today_open_time' => $today_open_time,
        ];
        return view('pages.checkout-final', $data);
    }

    protected function cardLists()
    {
        $list = collect([
            [
                'image' => 'ecommerce/images/marketplace/item-1.png',
                'title' => 'A 45 Carats Tanzanite and Diamond Pendant',
                'size' => '15x24x10cm',
                'color' => 'White',
                'weight' => '20lbs',
                'price' => '$2000'
            ],
            [
                'image' => 'ecommerce/images/marketplace/item-2.png',
                'title' => 'Botter Pinot Grigio Doc 2017 - 6 Paintings',
                'size' => '15x24x10cm',
                'color' => 'White',
                'weight' => '20lbs',
                'price' => '$4500'
            ],
            [
                'image' => 'ecommerce/images/marketplace/item-3.png',
                'title' => 'Botter Pinot Grigio Doc 2017 - 6 Paintings',
                'size' => '15x24x10cm',
                'color' => 'White',
                'weight' => '20lbs',
                'price' => '$4500'
            ]
        ]);
        return $list;
    }

    public function save_payment_info(Request $request)
    {
        $customer_id = $request->hid_customer_id;
        $item_arr = json_decode($request->hid_item_arr_id, true);
        $item_id_arr = [];

        DB::beginTransaction();
        try {
            if (isset($item_arr['items'])) {
                foreach ($item_arr['items'] as $item) {
                    // print_r($item['item_id']);
                    // array_push($item_id_arr, $item['item_id']);
                    $response = Item::where('status', '=', Item::_SOLD_)->where('id', '=', $item['id'])->count();
                    $date = Carbon::now();
                    try {
                        if ($response == 0) {
                            $item_id = $item['id'];
                            $getItemDetail = $this->itemRepository->getItemDetail($item_id);
                            // $payload['status'] = 'Waiting';
                            $payload['buyer_id'] = $customer_id;
                            // $this->itemRepository->update($item_id, $payload, true, 'Waiting');
                            $sold_price_inclusive_gst = $getItemDetail->price;
                            $sold_price_exclusive_gst = ($getItemDetail->price / 1.08);

                            Item::where('id', $item_id)->update(
                                [
                                    'buyer_id'      => $customer_id,
                                    'status'        => Item::_SOLD_,
                                    'sold_price' => $getItemDetail->price,
                                    'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
                                    'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
                                    'sold_date' => $date,
                                ]
                            );

                            ItemLifecycle::where('item_id', $item_id)->where('type', $getItemDetail->type)->update(
                                [
                                    'buyer_id'      => $customer_id,
                                    'status'        => Item::_SOLD_,
                                    'sold_price' => $getItemDetail->price,
                                    'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
                                    'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
                                    'sold_date' => $date
                                ]
                            );
                        } else {
                            DB::rollback();
                            return \Response::json(array('status'=>'-3','message'=>'One of your items is already sold!!'));
                        }
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(array('status'=>'-3','message'=>$e->getMessage()));
                    }
                }
            }
            DB::commit();

            return response()->json(array('status' => '1','message'=>'Update Status Successfully.'));
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    protected function packData($request)
    {
        if ($request->chk_ship) {
            $payload['ship_or_pick'] = 'ship';
        }
        if ($request->chk_pickup) {
            $payload['ship_or_pick'] = 'pick';
        }
        $payload['country_id'] = $request->country_id;
        $payload['country'] = $request->country_id;
        $payload['firstname'] = $request->firstname;
        $payload['lastname'] = $request->lastname;
        $payload['address'] = $request->address;
        $payload['city'] = $request->city;
        $payload['state'] = $request->state;
        $payload['zip_code'] = $request->zip_code;
        $payload['daytime_phone'] = $request->phone;

        return $payload;
    }

    public function checkoutCheckstatus(Request $request)
    {
        $status = '';
        $item_arr = json_decode($request->items, true);

        $result_array = $this->itemRepository->getNotInMarketplaceItemsForCheckout($item_arr);

        $not_in_mp_items = [];
        if (!$result_array->isEmpty()) {
            foreach ($result_array as $value) {
                array_push($not_in_mp_items, $value->name);
            }
        }

        $data = [
            'status'=>(count($not_in_mp_items) > 0)?'fail':'success',
            'returnItems'=>$not_in_mp_items
        ];
        return json_encode($data);
    }

    public function make_primary_address(Request $request)
    {
        $address_id = $request->checked_address_id;
        $customer_id = Auth::guard('customer')->user()->id;

        try {
            DB::table('customer_addresses')->where('customer_id', '=', $customer_id)->where('is_primary', '=', "1")->update(['is_primary' => '0']);

            DB::table('customer_addresses')->where('id', '=', $address_id)->where('customer_id', '=', $customer_id)->update(['is_primary' => '1']);

            return response()->json(array('status' => '1','message'=>''));
        } catch (\Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function save_checkout_address(Request $request)
    {
        $customer_id = $request->hid_customer_id;

        $ship_or_pick = '';
        $address_nickname = '';
        $state = '';
        if ($request->chk_ship) {
            $ship_or_pick = 'ship';
        }
        if ($request->chk_pickup) {
            $ship_or_pick = 'pick';
        }
        $country_id = $request->country_id;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $address = $request->address;
        $city = $request->city;
        // $zip_code = $rsequest->zip_code;
        $postalcode = $request->postalcode;
        $daytime_phone = $request->daytime_phone;
        $delivery_instruction = $request->delivery_instruction;
        if ($request->address_nickname) {
            $address_nickname =  $request->address_nickname;
        }

        if ($request->state) {
            $state = $request->state;
        }

        try {
            $address_id = DB::table('addresses')->insertGetId(
                ['type' => 'shipping', 'ship_or_pick' => $ship_or_pick, 'country_id' => $country_id, 'firstname' => $firstname, 'lastname' => $lastname, 'address' => $address, 'city' => $city, 'state' => $state, 'postalcode' => $postalcode, 'daytime_phone' => $daytime_phone, 'address_nickname' => $address_nickname, 'delivery_instruction' => $delivery_instruction]
            );

            $inseted_id = DB::table('customer_addresses')->insertGetId(
                ['customer_id' => $customer_id, 'address_id' => $address_id]
            );
            session()->flash('chk_ship', 'on');
            return response()->json(array('status' => '1','message'=>'Update Status Successfully.'));
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    protected function getItemCategoryData($itemData, $item)
    {
        $result = [];
        if ($item->additional_notes != null) {
            $result['Additional Information'] = $item->additional_notes;
        }
        if ($item->brand != null) {
            $result['Brand'] = $item->brand;
        }

        // if ($item->condition != null && ($item->condition == 'no_condition' || $item->condition == 'minor_signs')) {
        //     $result['Condition'] = 'No obvious condition issues';
        // }
        // if ($item->condition != null && $item->condition == 'minor_signs') {
        //     $result['Condition'] = 'Minor signs of wear commensurate with age and use';
        // }
        // if ($item->condition != null && $item->condition == 'specific_condition') {
        //     $result['Condition'] = $item->specific_condition_value;
        // }
        if ($item->condition != null) {
            $result['Condition'] = Item::getConditionValue($item->condition);
            if($item->condition == 'specific_condition' || $item->condition == 'general_condition') {
                $result['Condition'] = $item->specific_condition_value;
            }
        }

        if ($item->category_id != null) {
            $result['Category'] = $item->category->name;
        }
        if ($item->sub_category != null) {
            $result['Sub Category'] = $item->sub_category;
        }
        if ($item->provenance != null) {
            $result['Provenance'] = $item->provenance;
        }
        if ($item->weight != null) {
            $result['Weight'] = $item->weight;
        }

        if ($itemData != null) {
            foreach ($itemData as $key => $value) {
                if ($value != null) {
                    if (array_key_exists($key, $result)) {
                        $value_str = $result[$key].'/ '.$value;
                        // dd($value_str);
                        $result[$key] = $value_str;
                    } else {
                        $result[$key] = $value;
                    }
                }
            }
        }

        ksort($result);
        return $result;
    }
}