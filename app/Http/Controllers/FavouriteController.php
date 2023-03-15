<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Helpers\NHelpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\SampleHelper;

use App\Helpers\StorageHelper;
use App\Repositories\CMSRepository;

use App\Repositories\ItemRepository;

use App\Repositories\BannerRepository;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Session;
use App\Repositories\CategoryRepository;
use App\Repositories\FavouritesRepository;
use App\Modules\SysConfig\Models\SysConfig;
use Illuminate\Contracts\Support\Renderable;

class FavouriteController extends Controller
{
    protected $bannerRepository;
    protected $cmsRepository;
    protected $itemRepository;
    protected $categoryRepository;
    protected $countryRepository;
    protected $favouritesRepository;

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
        CountryRepository $countryRepository,
        FavouritesRepository $favouritesRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->cmsRepository = $cmsRepository;
        $this->bannerRepository = $bannerRepository;
        $this->categoryRepository = $categoryRepository;
        $this->countryRepository = $countryRepository;
        $this->favouritesRepository = $favouritesRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */

    public function index()
    {

    }

    public function saveFavouriteInfo(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $item_id = $request->item_id;

        $result = $this->favouritesRepository->getFavouriteItem($customer_id, $item_id);

        try {
            if($result > 0) {
                $result = $this->favouritesRepository->makeUnFavouriteItem($customer_id, $item_id);
                return response()->json(array('status' => '2','message'=>'Item is removed from your Favourite list Successfully!.'));
            }else{
                $payload['customer_id'] = $customer_id;
                $payload['item_id'] = $item_id;

                $result = $this->favouritesRepository->makeFavouriteItem($payload);
                return response()->json(array('status' => '1','message'=>'Item is added to your Favourite list Successfully!.'));
            }
        } catch (\Exception $e) {
            Session::put('error', $e->getMessage());
            DB::rollback();
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }
}
