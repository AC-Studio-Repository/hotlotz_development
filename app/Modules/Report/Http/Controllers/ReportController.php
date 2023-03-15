<?php

namespace App\Modules\Report\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Report\Models\Report;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Customer\Models\Customer;
use App\Modules\Report\Http\Requests\StoreReportRequest;
use App\Modules\Report\Http\Requests\UpdateReportRequest;
use App\Modules\Report\Http\Repositories\ReportRepository;
use App\Modules\Auction\Http\Repositories\AuctionRepository;

class ReportController extends Controller
{
    protected $reportRepository;
    protected $auctionRepository;
    public function __construct(ReportRepository $reportRepository,AuctionRepository $auctionRepository){
        $this->reportRepository = $reportRepository;
        $this->auctionRepository = $auctionRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $reports = $this->reportRepository->all([], false, 100);

        $data = [
            'reports' => [],
        ];
        return view('report::index',$data);
    }

    public function unsoldPostAuction()
    {
        return view('report::post_auction_report_unsold');
    }


    public function getUnsoldPostAuctionTable(Request $request)
    {
        $auction = Auction::where('auctions.title', request()->search_text)->first();
        if ($auction) {
            $saleReports = $this->auctionRepository->generateSaleReport($auction, request()->seller_id, 'UnSold');
            $customers = Customer::whereIn('id', collect($this->auctionRepository->generateSaleReport($auction, null, 'UnSold'))->pluck('seller_id'))->get();
            if (sizeof($saleReports) > 0) {
                $returnHTML = view('report::extends.post_auction_report_table', [
                    'saleReports' => $saleReports,
                    'customers' => $customers,
                    'seller' => request()->seller_id
                ])->render();
            }else{
                return response()->json(array('status' => 'fail','message'=>'Auction not found.'));
            }

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML));
        }else{
            return response()->json(array('status' => 'fail','message'=>'Auction not found.'));
        }
    }

    public function oneTreePlantedReport(Request $request)
    {
        $items_count = Item::whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                    ->where( function($query) {
                        $query->where('items.category_id',5);//Furniture
                        $query->orWhere('items.is_tree_planted', 'Y');
                    })
                    ->count();

        if($items_count <= 0){
            $items_count = "N/A";
        }


        $items = Item::whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                ->where( function($query) {
                    $query->where('items.category_id',5);//Furniture
                    $query->orWhere('items.is_tree_planted', 'Y');
                })
                ->orderBy('items.sold_date', 'DESC')
                ->select('items.*')
                ->paginate(10);

        $data = [
            'items' => $items,
            'items_count' => $items_count,
        ];
        return view('report::one_tree_planted_report', $data);
    }

    public function oneTreePlantedFilter(Request $request)
    {
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            $query = Item::whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                    ->where( function($query) {
                        $query->where('items.category_id',5);//Furniture
                        $query->orWhere('items.is_tree_planted', 'Y');
                    })
                    ->orderBy('items.sold_date', 'DESC')
                    ->select('items.*');

            //Filter by Date Range
            if(isset($request->start_date) || isset($request->end_date) ) {
                // $query->whereBetween('items.sold_date', [date('Y-m-d H:i:s', strtotime($request->start_date)), date('Y-m-d H:i:s', strtotime($request->end_date))]);
                $query->whereDate('items.sold_date','>=', $request->start_date);
                $query->whereDate('items.sold_date','<=', $request->end_date);
            }

            $items = $query->paginate($per_page);
            $items_count = $query->count();
            if($items_count <= 0){
                $items_count = "N/A";
            }

            $returnHTML = view('report::extends.one_tree_planted_table', [
                'items' => $items,
                'items_count' => $items_count,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e));
        }
    }

    public function preciousStonePreciousMetalReport(Request $request)
    {
        $items_count = Item::whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                    ->where( function($query) {
                        $query->where('items.category_id',6);//Jewellery
                        $query->orWhere('items.is_pspm', 'Y');
                    })
                    ->count();

        if($items_count <= 0){
            $items_count = "N/A";
        }


        $items = Item::whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                ->where( function($query) {
                    $query->where('items.category_id',6);//Jewellery
                    $query->orWhere('items.is_pspm', 'Y');
                })
                ->orderBy('items.sold_date', 'DESC')
                ->select('items.*')
                ->paginate(10);

        $data = [
            'items' => $items,
            'items_count' => $items_count,
        ];
        return view('report::pspm_report', $data);
    }

    public function preciousStonePreciousMetalFilter(Request $request)
    {
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            $query = Item::whereIn('items.status', [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                    ->where( function($query) {
                        $query->where('items.category_id',6);//Jewellery
                        $query->orWhere('items.is_pspm', 'Y');
                    })
                    ->orderBy('items.sold_date', 'DESC')
                    ->select('items.*');

            //Filter by Date Range
            if(isset($request->start_date) || isset($request->end_date) ) {
                $query->whereDate('items.sold_date','>=', $request->start_date);
                $query->whereDate('items.sold_date','<=', $request->end_date);
            }

            $items = $query->paginate($per_page);
            $items_count = $query->count();
            if($items_count <= 0){
                $items_count = "N/A";
            }

            $returnHTML = view('report::extends.pspm_table', [
                'items' => $items,
                'items_count' => $items_count,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e));
        }
    }


}