<?php

namespace App\Http\Controllers\Search;

use App\Helpers\MenuHelper;
use App\Http\Controllers\Controller;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\SearchRepository;
use Auth;
use Cookie;
use DB;
use Hash;
use Illuminate\Http\Request;
use Response;
use View;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Models\CategoryProperty;

class SearchController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */

    protected $searchRepository;
    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function searchResult(Request $request)
    {
        $search_value = $request->txtsearch;
        $type = "";
        $sub_category = "";
        $price = "";
        $category = 0;

        $searchResultList = $this->searchRepository->getSearchData($search_value);

        $menus = MenuHelper::getSearchMenu();
        $title = $search_value;
        $today_open_time = SysConfig::getTodayOpenTime();

        $categories = Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id')->all();

        $data = [
            'searchResultList' => $searchResultList,
            'menus' => $menus,
            'today_open_time' => $today_open_time,
            'title' => $title,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'categories' => $categories,
            'search_query' => $search_value,
            'type' => $type,
            'category' => $category,
            'sub_category' => $sub_category,
            'price' => $price
        ];

        return view('pages.search.search_result', $data)->withQuery($search_value);
    }

    public function search(Request $request)
    {
        $type = $request->type;
        $category = $request->category_id;
        $sub_category = '';
        $price = $request->price;
        $search_query = $request->hid_search_query;
        $menus = MenuHelper::getSearchMenu();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = $search_query;
        $categories = Category::where('parent_id', null)->orderBy('name')->pluck('name', 'id')->all();

        if ($request->sub_category) {
            $sub_category = $request->category_id;
        }

        // $searchResultList = $this->searchRepository->getSearchDataAll($type, $category, $sub_category, $price, $search_query);
        $searchResultList = $this->searchRepository->getSearchData($search_query, $type, $category, $sub_category, $price);

        $data = [
            'searchResultList' => $searchResultList,
            'menus' => $menus,
            'today_open_time' => $today_open_time,
            'title' => $title,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'categories' => $categories,
            'search_query' => $search_query,
            'type' => $type,
            'category' => $category,
            'sub_category' => $sub_category,
            'price' => $price
        ];

        return view('pages.search.search_result_all', $data);
    }

    public function getSubCategory(Request $request)
    {
        $category_id = $request->category_id;
        try {
            $category = Category::where('id', $category_id)->first();
            $sub_category = CategoryProperty::where('category_id', $category_id)->where('key', 'Sub Category')->pluck('value')->first();
            $subcategories = [];
            if ($sub_category && $sub_category != null) {
                $sub_category = explode(',', $sub_category);

                foreach ($sub_category as $key => $value) {
                    $subcategories[$value] = $value;
                }
            }

            $data = [];
            $data['category_name'] = $category?$category->name:null;
            $data['subcategories'] = $subcategories;

            return \Response::json(array('status'=>'1','message'=>'Get Subcategory Successfully.','data'=>$data));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }
}