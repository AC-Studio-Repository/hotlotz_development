<?php

namespace App\Modules\Marketplace\Http\Controllers;

use DB;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Modules\Item\Models\Item;
use App\Http\Controllers\Controller;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Marketplace\Models\Marketplace;
use App\Modules\Marketplace\Http\Requests\StoreMarketplaceRequest;
use App\Modules\Marketplace\Http\Requests\UpdateMarketplaceRequest;
use App\Modules\Marketplace\Http\Repositories\MarketplaceRepository;

class MarketplaceController extends Controller
{
    protected $marketplaceRepository;
    public function __construct(MarketplaceRepository $marketplaceRepository)
    {
        $this->marketplaceRepository = $marketplaceRepository;
    }

    public function newMarketplaceItems(Request $request)
    {
        // $select2customers = Customer::getSelect2CustomerData();

        $today = date('Y-m-d');
        $last_week = date("Y-m-d", strtotime("-7 days"));
        // $last_2week = date("Y-m-d", strtotime("-14 days"));
        // dd($last_2week);

        $items = Item::where('items.status', Item::_IN_MARKETPLACE_)
                ->where( function($query) use ($last_week, $today) {
                    $query->where( function($query2) use ($last_week, $today) {
                        $query2->where('items.lifecycle_status', 'Marketplace');
                        $query2->whereDate('entered_marketplace_date','>=', $last_week);
                        $query2->whereDate('entered_marketplace_date','<=', $today);
                    });
                    $query->orWhere( function($query2) use ($last_week, $today) {
                        $query2->where('items.lifecycle_status', Item::_CLEARANCE_);
                        $query2->whereDate('entered_clearance_date','>=', $last_week);
                        $query2->whereDate('entered_clearance_date','<=', $today);
                    });
                })
                ->orderBy('entered_marketplace_date', 'DESC')
                ->orderBy('entered_clearance_date', 'DESC')
                ->select('items.*')
                ->paginate(10);

        $data = [
            'items' => $items,
            // 'select2customers' => $select2customers,
            'new_addition_selected_all' => 'N',
            'statuses' => [''=>'All', 'Marketplace'=>'Marketplace', 'Clearance'=>'Clearance'],
        ];
        return view('marketplace::new_additions', $data);
    }

    public function newAdditionFilter(Request $request)
    {
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;

            // $sort_by = isset($request->sort_by)?$request->sort_by:'entered_marketplace_date';
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            $today = date('Y-m-d');
            $last_week = date("Y-m-d", strtotime("-7 days"));

            $query = Item::where('items.status', Item::_IN_MARKETPLACE_)
                    ->where( function($query) use ($last_week, $today) {
                        $query->where( function($query2) use ($last_week, $today) {
                            $query2->where('items.lifecycle_status', 'Marketplace');
                            $query2->whereDate('entered_marketplace_date','>=', $last_week);
                            $query2->whereDate('entered_marketplace_date','<=', $today);
                        });
                        $query->orWhere( function($query2) use ($last_week, $today) {
                            $query2->where('items.lifecycle_status', Item::_CLEARANCE_);
                            $query2->whereDate('entered_clearance_date','>=', $last_week);
                            $query2->whereDate('entered_clearance_date','<=', $today);
                        });
                    })
                    ->orderBy('entered_marketplace_date', 'desc')
                    ->orderBy('entered_clearance_date', 'desc')
                    ->select('items.*');

            //Filter by Seller
            if (isset($request->seller)) {
                $query->where('items.customer_id', $request->seller);
            }

            //Filter by Status
            if (isset($request->status)) {
                $query->where('items.lifecycle_status', $request->status);
            }

            $items = $query->paginate($per_page);

            $returnHTML = view('marketplace::new_addition_table', [
                'items' => $items,
                'new_addition_selected_all' => 'N',
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML, 'new_addition_selected_all' => 'N'));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e));
        }
    }

    public function soldMarketplaceItems(Request $request)
    {
        // $select2customers = Customer::getSelect2CustomerData();

        $itemLifecycles = ItemLifecycle::whereIn('item_lifecycles.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                ->whereIn('item_lifecycles.type', ['marketplace','clearance'])
                ->select('item_id')
                ->groupBy('item_id');

        $items = Item::whereIn('items.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                ->whereIn('items.lifecycle_status', [Item::_MARKETPLACE_,Item::_CLEARANCE_])
                ->joinSub($itemLifecycles, 'item_lifecycles', function ($join) {
                    $join->on('items.id', '=', 'item_lifecycles.item_id');
                })
                ->select('items.*')
                ->orderBy('items.sold_date', 'desc')
                ->paginate(10);

        $tags = [''=>'--- Select Tag ---','in_storage'=>'In Storage','dispatched'=>'Dispatched'];

        $data = [
            'items' => $items,
            // 'select2customers' => $select2customers,
            'sold_item_selected_all' => 'N',
            'tags' => $tags,
        ];
        return view('marketplace::sold_items', $data);
    }

    public function soldItemFilter(Request $request)
    {
        // dd($request->all());
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;

            $sort_by = isset($request->sort_by)?$request->sort_by:'items.sold_date';
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            $itemLifecycles = ItemLifecycle::whereIn('item_lifecycles.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                ->whereIn('item_lifecycles.type', ['marketplace','clearance'])
                ->select('item_id')
                ->groupBy('item_id');

            $query = Item::whereIn('items.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                    ->whereIn('items.lifecycle_status', [Item::_MARKETPLACE_,Item::_CLEARANCE_])
                    ->joinSub($itemLifecycles, 'item_lifecycles', function ($join) {
                        $join->on('items.id', '=', 'item_lifecycles.item_id');
                    })
                    ->select('items.*');

            //Filter by Buyer
            if (isset($request->seller)) {
                $query->where('items.customer_id', $request->seller);
            }

            //Filter by Buyer
            if (isset($request->buyer)) {
                $query->where('items.buyer_id', $request->buyer);
            }

            if(isset($request->start_date) || isset($request->end_date) ) {
                $query->whereBetween('items.sold_date', [date('Y-m-d H:i:s', strtotime($request->start_date)), date('Y-m-d H:i:s', strtotime($request->end_date))]);
            }

            //Filter by Tag
            if (isset($request->tag)) {
                if($request->tag == 'dispatched'){
                    $query->where('items.tag', $request->tag);
                }
                if($request->tag == 'in_storage'){
                    $query->whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_, Item::_STORAGE_, Item::_UNSOLD_]);
                    $query->where(function ($query2) use ($request) {
                        $query2->where('items.tag', $request->tag);
                        $query2->orWhere('items.tag', null);
                    });
                }
            }

            $items = $query->orderBy($sort_by, $sort_type)->paginate($per_page);
            // dd($items->all());

            $returnHTML = view('marketplace::sold_item_table', [
                'items' => $items,
                'sold_item_selected_all' => 'N'
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML, 'sold_item_selected_all' => 'N'));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [
            'marketplaces' => [],
        ];
        return view('marketplace::index', $data);
    }

    public function generateLabel(Request $request)
    {
        $items = json_decode($request->items);
        // dd($items);

        $data = $this->marketplaceRepository->generateLabel($items);

        $opciones_ssl=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $img_path = asset('ecommerce/images/logo/logo.jpg');
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $imgage = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($imgage);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $data['logo'] = $path_img;

        view()->share('data',$data);
        $pdf = PDF::loadView('marketplace::pdf.generate_label_dom', $data);
        return $pdf->stream();

        // return view('marketplace::pdf.generate_label', compact('data'));
    }

    public function generateBuyerLabel(Request $request)
    {
        $items = json_decode($request->items);
        // dd($items);

        $data = $this->marketplaceRepository->generateBuyerLabel($items);

        $opciones_ssl=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $img_path = asset('ecommerce/images/logo/logo.jpg');
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $imgage = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($imgage);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $data['logo'] = $path_img;

        view()->share('data',$data);
        $pdf = PDF::loadView('marketplace::pdf.generate_buyer_label_dom', $data);
        return $pdf->stream();

        // return view('marketplace::pdf.generate_buyer_label', compact('data'));
    }

    public function marketplaceAllItems(Request $request)
    {
        // $select2customers = Customer::getSelect2CustomerData();

        $items = Item::where('items.status', Item::_IN_MARKETPLACE_)
                ->orderBy('entered_marketplace_date', 'DESC')
                ->orderBy('entered_clearance_date', 'DESC')
                ->select('items.*')
                ->paginate(10);

        $data = [
            'items' => $items,
            // 'select2customers' => $select2customers,
            'mp_selected_all' => 'N',
            'statuses' => [''=>'All', 'Marketplace'=>'Marketplace', 'Clearance'=>'Clearance'],
        ];
        return view('marketplace::all_items', $data);
    }

    public function marketplaceAllItemsFilter(Request $request)
    {
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;

            // $sort_by = isset($request->sort_by)?$request->sort_by:'entered_marketplace_date';
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            $query = Item::where('items.status', Item::_IN_MARKETPLACE_)
                    ->orderBy('entered_marketplace_date', 'desc')
                    ->orderBy('entered_clearance_date', 'desc')
                    ->select('items.*');

            //Filter by Seller
            if (isset($request->seller)) {
                $query->where('items.customer_id', $request->seller);
            }

            //Filter by Status
            if (isset($request->status)) {
                $query->where('items.lifecycle_status', $request->status);
            }

            $items = $query->paginate($per_page);

            $returnHTML = view('marketplace::all_items_table', [
                'items' => $items,
                'mp_selected_all' => 'N',
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML, 'mp_selected_all' => 'N'));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e));
        }
    }

    public function generateLabelMpAll(Request $request)
    {
        $items = json_decode($request->items);
        $data = $this->marketplaceRepository->generateLabelMpAll($items);

        $opciones_ssl=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $img_path = asset('ecommerce/images/logo/logo.jpg');
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $imgage = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($imgage);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $data['logo'] = $path_img;

        view()->share('data',$data);
        $pdf = PDF::loadView('marketplace::pdf.generate_label_dom', $data);
        return $pdf->stream();
    }

}
