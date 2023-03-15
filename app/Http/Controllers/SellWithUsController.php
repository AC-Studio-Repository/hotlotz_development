<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Helpers\NHelpers;
use App\Helpers\MenuHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;

use App\Modules\Item\Models\Item;
use App\Repositories\CMSRepository;

use App\Modules\Item\Models\ItemImage;
use App\Repositories\BannerRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CategoryRepository;

use App\Events\Item\CreateThumbnailEvent;

use App\Modules\SysConfig\Models\SysConfig;
use App\Events\Item\SubmissionReceivedEvent;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Item\Models\ItemFeeStructure;
use App\Modules\Item\Http\Repositories\ItemRepository;

class SellWithUsController extends Controller
{
    protected $bannerRepository;
    protected $cmsRepository;
    protected $itemRepository;
    protected $categoryRepository;
    protected $countryRepository;

    /**
     * Create a new controller instance.
     *
     * @param BannerRepository $bannerRepository
     * @param CMSRepository $cmsRepository
     * @param ItemRepository $itemRepository
     * @param CategoryRepository $categoryRepository
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        BannerRepository $bannerRepository,
        CMSRepository $cmsRepository,
        ItemRepository $itemRepository,
        CategoryRepository $categoryRepository,
        CountryRepository $countryRepository
    ) {
        $this->middleware(['auth:customer','verified']);

        $this->itemRepository = $itemRepository;
        $this->cmsRepository = $cmsRepository;
        $this->bannerRepository = $bannerRepository;
        $this->categoryRepository = $categoryRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */

    public function index()
    {
        $title = "Sell with Us";
        $menus = MenuHelper::getMenuFooter();
        $today_open_time = SysConfig::getTodayOpenTime();
        $info = $this->bannerRepository->getSellWithUs();
        $cms = $this->cmsRepository->getSellWithUs();
        $faq = $this->cmsRepository->getSellWithUsFAQ();
        $categories = $this->categoryRepository->getAllCategories();
        $countries = $this->countryRepository->getAllCountries();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'info' => $info,
            'cms' => $cms,
            'faq' => $faq,
            'categories' => $categories,
            'countries' => $countries,
        ];

        return view('pages.sell_with_us', $data);
    }

    public function submitOld(Request $request)
    {
        // dd($request->all());
        $title = $request->title;
        if (isset($request->category_id)) {
            $category_id = $request->category_id;
        }
        $description = $request->description;
        $photo_sessions = $request->photo_session;

        $title_arr = [];
        $category_arr = [];
        $item_arr = [];
        $itemIds = [];

        $customer_id = Auth::guard('customer')->user()->id;

        foreach ($title as $key=>$itm_title) {
            $item_code_data = Item::generateItemCode($customer_id);
            $item_number = $item_code_data['item_code'];
            $item_code_id = $item_code_data['item_code_id'];

            \Log::info("Item Number : " . $item_number);

            $payload = array( "title" => $itm_title, "name" => $itm_title, "status" => Item::_SWU_, "permission_to_sell" => "N", "customer_id" => $customer_id, "item_number" => $item_number, "item_code_id" => $item_code_id);
            array_push($title_arr, $payload);
        }

        foreach ($category_id as $key=>$category) {
            array_push($category_arr, array_merge($title_arr[$key], array("category_id" => $category)));
        }
        foreach ($description as $key=>$itm_description) {
            array_push($item_arr, array_merge($category_arr[$key], array("long_description" => $itm_description)));
        }

        $itemIds = [];

        DB::beginTransaction();
        try {
            foreach ($category_arr as $key => $item) {
                $item_code_data = Item::generateItemCode($customer_id);
                $item['registration_date'] = date('Y-m-d H:i:s');
                $item['cataloguer_id'] = 0;

                $item['cataloguer_id'] = 1;

                $item = $this->itemRepository->create($item);

                $itemIds[] = $item->id;

                if ($item) {
                    $item_path = 'item/'.$item->id;
                    $photoTemps = StorageHelper::get('temp/'.$photo_sessions[$key]);

                    foreach ($photoTemps as $photoTemp) {
                        StorageHelper::store($item_path, array($photoTemp['data']), false, true);
                    }

                    $photoItems = StorageHelper::get($item_path);

                    foreach ($photoItems as $result) {
                        $insert_item_imgs = [
                            'file_name' => $result['name'],
                            'file_path' => $item_path.'/'.$result['name'],
                            'full_path' => $result['data'],
                        ];

                        $item_img_id = ItemImage::insertGetId($insert_item_imgs + NHelpers::created_updated_at());

                        $item_image = ItemImage::find($item_img_id);
                        $item_image->item_id = $item->id;
                        $item_image->save();
                    }
                    StorageHelper::delete('temp/'.$photo_sessions[$key]);

                    session()->forget($photo_sessions[$key]);

                    DB::commit();
                }
            }
            event(new SubmissionReceivedEvent(Auth::guard('customer')->user(), $item_arr));
            return redirect(route('sell-with-us', ['item' => $item ]))->with('success', 'Thank you. Your item has been submitted.');

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            DB::rollback();
            $error = 'Server Error ! Item(s) Submitted Fail';
            return redirect()->back()->withInput()->with(['error' => $error]);
        }
    }

    public function submit(Request $request)
    {
        $photo_sessions = $request->photo_session;
        $customer_id = Auth::guard('customer')->user()->id;
        $item_data = [];
        $itemIds = [];

        DB::beginTransaction();
        try {
            for ($i=0; $i < count($request['title']); $i++) {
                $item_data[] = [
                    'name' => $request['title'][$i],
                    'category_id' => $request['category_id'][$i],
                    'description' => $request['description'][$i],
                ];
            }

            foreach ($item_data as $key => $item) {
                $item_code_data = Item::generateItemCode($customer_id);
                $payload = $this->packData($customer_id, $item, $item_code_data);
                // dd($payload);

                $item = $this->itemRepository->create($payload);

                if ($item) {
                    \Log::info('item_id : '.$item->id);
                    ItemFeeStructure::autoSaveSalesCommissionPayload($item->id);

                    $itemIds[] = $item->id;
                    $item_path = 'item/'.$item->id;
                    $photoTemps = StorageHelper::get('temp/'.$photo_sessions[$key]);

                    foreach ($photoTemps as $photoTemp) {
                        StorageHelper::store($item_path, array($photoTemp['data']), false, true);
                    }

                    $photoItems = StorageHelper::get($item_path);

                    foreach ($photoItems as $result) {
                        $insert_item_imgs = [
                            'file_name' => $result['name'],
                            'file_path' => $item_path.'/'.$result['name'],
                            'full_path' => $result['data'],
                        ];

                        $item_img_id = ItemImage::insertGetId($insert_item_imgs + NHelpers::created_updated_at());

                        $item_image = ItemImage::find($item_img_id);
                        $item_image->item_id = $item->id;
                        $item_image->save();
                        event(new CreateThumbnailEvent($item->id));
                    }
                    StorageHelper::delete('temp/'.$photo_sessions[$key]);

                    session()->forget($photo_sessions[$key]);

                }
            }
            DB::commit();

            event(new SubmissionReceivedEvent(Auth::guard('customer')->user(), $item_data));
            return redirect(route('sell-with-us'))->with('success', 'Thank you. Your item has been submitted.');

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            DB::rollback();
            $error = 'Server Error ! Item(s) Submitted Fail';
            return redirect()->back()->withInput()->with(['error' => $error]);
        }
    }

    public function packData($customer_id, $data, $item_code_arr)
    {
        $payload = [];
        $payload['registration_date'] = date('Y-m-d H:i:s');
        $payload['status'] = Item::_SWU_;
        $payload['permission_to_sell'] = 'N';
        $payload['cataloguing_needed'] = 'Y';
        $payload['is_cataloguing_approved'] = 'N';
        $payload['is_valuation_approved'] = 'N';
        $payload['is_fee_structure_approved'] = 'N';
        $payload['is_fee_structure_needed'] = 'Y';
        $payload['fee_type'] = 'sales_commission';

        $payload['cataloguer_id'] = 1; //*
        $payload['name'] = $data['name']; //*
        $payload['title'] = $data['name']; //*
        $payload['customer_id'] = $customer_id; //*

        $payload['is_new'] = 'N';
        $payload['is_tree_planted'] = 'N';
        $payload['is_highlight'] = 'N';
        $payload['brand'] = null;

        $payload['item_number'] = $item_code_arr['item_code'];
        $payload['item_code_id'] = $item_code_arr['item_code_id'];
        $payload['location'] = "Saleroom";
        $payload['long_description'] = $data['description'];

        $payload['category_id'] = $data['category_id'];
        $payload['sub_category'] = null;

        $payload['condition'] = null;
        $payload['specific_condition_value'] = null;
        $payload['provenance'] = null;
        $payload['designation'] = null;

        $payload['is_dimension'] = 'N';
        $payload['dimensions'] = null;

        $payload['is_weight'] = 'N';
        $payload['weight'] = null;

        $payload['additional_notes'] = null;
        $payload['internal_notes'] = null;

        $payload['category_data'] = [];
        $payload['is_pro_photo_need'] = 'Y';

        return $payload;
    }

    public function upload(Request $request)
    {
        try {
            if ($request->session_photo) {
                $random = $request->session_photo;
            } else {
                $random = Str::random('10');
            }

            if ($request->file('file')->extension() == 'jpg' || $request->file('file')->extension() == 'jpeg') {
                $imageArray = [];

                $result = StorageHelper::add($path = 'temp/'.$random, $files = array($request->file));
                $imageArray[] = $result;
                session([$random => $result]);
            }
            $data = [
                'status'=> 1,
                'ajax_data'=> $random
            ];
        } catch (\Throwable $th) {

            $data = [
                'status'=> -1,
                'ajax_data'=> '',
            ];
        }

        return response()->json($data);
    }

    public function getMoreFAQ(Request $request)
    {
        $offset = $request->offset;
        $limit = $request->limit;

        $faq = $this->cmsRepository->getSellWithUsFAQ($limit, $offset);

        $data = [
            'status'=>1,
            'ajax_data'=>$faq,
            'append' => true
        ];
        return json_encode($data);
    }
}
