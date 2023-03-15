<?php

namespace App\Modules\Auction\Http\Controllers;

use DB;
use PDF;
use App\Helpers\NHelpers;
use App\Jobs\CheckAuction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Jobs\LifecycleStart;
use App\Modules\Item\Models\Item;
use App\Http\Controllers\Controller;
use App\Jobs\ItemLifecycleInAuction;
use App\Events\GAPAuctionCreateEvent;
use App\Events\GAPAuctionUpdateEvent;
use GAP\Api\AuctionApi as AuctionApi;
use App\Events\GAPAuctionPublishEvent;
use App\Modules\Item\Models\ItemImage;
use App\Events\Auction\LotReorderEvent;
use App\Modules\Auction\Models\Auction;
use Illuminate\Support\Facades\Storage;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\ItemLifecycle;
use App\Events\Client\SendKycBuyerEmailEvent;
use App\Events\Client\SendKycCompanySellerEmailEvent;
use App\Events\Client\SendKycIndividualSellerEmailEvent;
use App\Modules\Auction\Http\Requests\StoreAuctionRequest;
use App\Modules\Auction\Http\Requests\UpdateAuctionRequest;
use App\Modules\Auction\Http\Repositories\AuctionRepository;

class AuctionController extends Controller
{
    protected $auctionRepository;
    public function __construct(AuctionRepository $auctionRepository)
    {
        $this->auctionRepository = $auctionRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->closed == 'yes'){
            $auctions = Auction::where('is_closed', 'Y')->orderBy('timed_first_lot_ends', 'DESC')->paginate(10);
        }else{
            $auctions = Auction::where('is_closed', '!=', 'Y')->orderBy('timed_first_lot_ends', 'ASC')->paginate(10);
        }

        $data = [
            'auctions' => $auctions,
        ];
        return view('auction::index', $data);
    }

    public function filter(Request $request)
    {
        // dd($request->all());
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;

            $sort_by = isset($request->sort_by)?$request->sort_by:'auctions.created_at';
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            if($request->closed == 'yes'){
                $query = Auction::where('is_closed', 'Y')->orderBy('timed_first_lot_ends', 'DESC');
            }else{
                $query = Auction::where('is_closed', '!=', 'Y')->orderBy('timed_first_lot_ends', 'ASC');
            }

            //Filter by Title
            if (isset($request->search_text)) {
                $query->where('auctions.title', 'like', '%'.$request->search_text.'%');
            }

            $auctions = $query->paginate($per_page);

            $returnHTML = view('auction::_auction_index', [
                'auctions' => $auctions,
            ])->render();

            return response()->json(array('status' => '1','message'=>'Filter Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auction = app(Auction::class);
        $countries = DB::table('countries')->pluck('name', 'id');
        $countries_codes = DB::table('countries')->pluck('country_code', 'id');//fake fake codes* need to  corrected
        $currencies = DB::table('countries')->pluck('currency_code', 'id');//fake fake currencies* need to  corrected
        $types = [
            'Timed'=>'Timed',
            'Live'=>'Live'
        ];

        $saleroom_categories = Auction::getSaleroomCategory();
        // dd($saleroom_categories);

        $sale_types = Auction::getSaleTypes();

        $data = [
            'types' => $types,
            'auction' => $auction,
            'countries' => $countries,
            'countries_codes' => $countries_codes,
            'currencies' => $currencies,
            'saleroom_categories' => isset($saleroom_categories['error'])?[]:$saleroom_categories,
            'sale_types' => $sale_types,
        ];
        return view('auction::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuctionRequest $request)
    {
        DB::beginTransaction();
        try {
            $payload = Auction::packData($request);
            \Log::info('Auction create - payload : '.print_r($payload, true));
            // dd($payload);
            $auction = $this->auctionRepository->create($payload);

            if ($auction) {
                if (isset($request->auction_image)) {
                    $file_path = Storage::put('auction/'.$auction->id, $request->auction_image);
                    $file_name = str_replace('auction/'.$auction->id.'/', '', $file_path);
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->auctionRepository->update($auction->id, $image_data, true);
                }

                if (isset($request->auction_banner)) {
                    $file_path = Storage::put('auction/banner/'.$auction->id, $request->auction_banner);
                    $file_name = str_replace('auction/banner/'.$auction->id.'/', '', $file_path);
                    $full_path = Storage::url($file_path);

                    $image_data['banner_file_name'] = $file_name;
                    $image_data['banner_file_path'] = $file_path;
                    $image_data['banner_full_path'] = $full_path;

                    $this->auctionRepository->update($auction->id, $image_data, true);
                }

                \Log::channel('gapLog')->info('call GAPAuctionCreateEvent');
                event(new GAPAuctionCreateEvent($auction));
                $updated_auction = Auction::find($auction->id);

                ##command out by MCT (8Feb2022)
                // if ($updated_auction->sr_auction_id != null) {
                //     ## Call CheckAuction job
                //     $datetime = new \Carbon\Carbon($updated_auction->timed_first_lot_ends);
                //     CheckAuction::dispatch($auction->id)->delay($datetime->addMinutes(10));
                //     \Log::info('Auction create - dispatch CheckAuction '.$auction->id);
                // }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $auction->getTitle()]));
                return redirect(route('auction.auctions.show', ['auction' => $auction ]));
            }
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Auction Create Failed!']));
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the auction
     *
     * @param Item $auction
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAuction(Auction $auction, $tab_name)
    {
        $auction->timed_start = date_format(date_create($auction->timed_start), 'Y-m-d h:i A');
        $auction->timed_first_lot_ends = date_format(date_create($auction->timed_first_lot_ends), 'Y-m-d h:i A');
        // dd($auction);

        $countries = DB::table('countries')->pluck('name', 'id');
        $countries_codes = DB::table('countries')->pluck('country_code', 'id');//fake fake codes* need to  corrected
        $currencies = DB::table('countries')->pluck('currency_code', 'id');//fake fake currencies* need to  corrected
        $types = [
            'Timed'=>'Timed',
            'Live'=>'Live'
        ];

        $lot_count = AuctionItem::where('auction_id',$auction->id)->count();
        // dd($lot_count);

        $data = [
            'tab_name' => $tab_name,
            'auction' => $auction,
            'countries' => $countries,
            'countries_codes' => $countries_codes,
            'currencies' => $currencies,
            'types' => $types,
            'sale_types' => Auction::getSaleTypes(),
            'create_lot_count' => 0,
            'lot_count' => $lot_count,
        ];

        if($tab_name == 'pre_auction'){
            // $auction_items = $this->auctionRepository->getPreAuctionItems($auction);
            // $total_lots = $this->auctionRepository->getPreAuctionItems($auction, 'total_count');
            // $total_starting_bid = $this->auctionRepository->getPreAuctionItems($auction, 'total_starting_bid');
            // $total_low_estimate = $this->auctionRepository->getPreAuctionItems($auction, 'total_low_estimate');
            // $total_high_estimate = $this->auctionRepository->getPreAuctionItems($auction, 'total_high_estimate');
            // $pre_auction_data = $this->auctionRepository->getPreAuctionItems($auction, 'pre_auction_data');
            // $auction_items = $pre_auction_data['preauction_items'];
            // $total_lots = $pre_auction_data['total_count'];
            // $total_starting_bid = $pre_auction_data['total_starting_bid'];
            // $total_low_estimate = $pre_auction_data['total_low_estimate'];
            // $total_high_estimate = $pre_auction_data['total_high_estimate'];

            $saleroom_categories = Auction::getSaleroomCategory();

            $create_lots = $this->auctionRepository->getAllPureAuctionItems($auction->id);
            $create_lot_count = count($create_lots);

            $data['saleroom_categories'] = isset($saleroom_categories['error'])?[]:$saleroom_categories;
            $data['create_lot_count'] = $create_lot_count;
            // $data['auction_items'] = $auction_items;
            // $data['total_lots'] = $total_lots;
            // $data['total_starting_bid'] = $total_starting_bid;
            // $data['total_low_estimate'] = $total_low_estimate;
            // $data['total_high_estimate'] = $total_high_estimate;
        }
        if($tab_name == 'auction_catalogue'){

        }
        if($tab_name == 'post_auction'){
            $total_settlement = $this->auctionRepository->getAuctionTotalSettlement($auction);
            $total_settlement = number_format($total_settlement, 2, '.', '');
            // $saleReports = $this->auctionRepository->generateSaleReport($auction, request()->seller_id);
            $lot_list = $this->auctionRepository->getLotsForClosedAuction($auction->id);

            $data['total_settlement'] = $total_settlement;
            $data['saleReports'] = [];
            $data['lot_list'] = $lot_list;
        }
        // dd($data);
        return view('auction::show', $data);
    }

    public function show(Auction $auction)
    {
        return redirect( route('auction.auctions.show_auction', [$auction, 'pre_auction']) );
        // $auction->timed_start = date_format(date_create($auction->timed_start), 'Y-m-d h:i A');
        // $auction->timed_first_lot_ends = date_format(date_create($auction->timed_first_lot_ends), 'Y-m-d h:i A');
        // // dd($auction);

        // $countries = DB::table('countries')->pluck('name', 'id');
        // $countries_codes = DB::table('countries')->pluck('country_code', 'id');//fake fake codes* need to  corrected
        // $currencies = DB::table('countries')->pluck('currency_code', 'id');//fake fake currencies* need to  corrected
        // $types = [
        //     'Timed'=>'Timed',
        //     'Live'=>'Live'
        // ];

        // $saleroom_categories = Auction::getSaleroomCategory();

        // $create_lots = $this->auctionRepository->getAllPureAuctionItems($auction->id);
        // $create_lot_count = count($create_lots);

        // $data = [
        //     'types' => $types,
        //     'auction' => $auction,
        //     'countries' => $countries,
        //     'countries_codes' => $countries_codes,
        //     'currencies' => $currencies,
        //     'saleroom_categories' => isset($saleroom_categories['error'])?[]:$saleroom_categories,
        //     'create_lot_count' => $create_lot_count,
        // ];
        // return view('auction::show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Auction $auction)
    {
        $auction = $this->auctionRepository->show('id', $auction->id, [], true);
        $auction->timed_start = date_format(date_create($auction->timed_start), 'Y-m-d h:i A');
        $auction->timed_first_lot_ends = date_format(date_create($auction->timed_first_lot_ends), 'Y-m-d h:i A');

        $countries = DB::table('countries')->pluck('name', 'id');
        $countries_codes = DB::table('countries')->pluck('country_code', 'id');
        $currencies = DB::table('countries')->pluck('currency_code', 'id');

        $types = [
            'Timed'=>'Timed',
            'Live'=>'Live'
        ];

        $saleroom_categories = Auction::getSaleroomCategory();

        $data = [
            'types' => $types,
            'auction' => $auction,
            'countries' => $countries,
            'countries_codes' => $countries_codes,
            'currencies' => $currencies,
            'saleroom_categories' => isset($saleroom_categories['error'])?[]:$saleroom_categories,
            'sale_types' => Auction::getSaleTypes(),
        ];
        return view('auction::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuctionRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $old_auction = Auction::find($id);
            $old_end_date = $old_auction->timed_first_lot_ends;

            // prepare variables
            $payload = Auction::packData($request);

            if (isset($request->auction_image)) {
                $file_path = Storage::put('auction/'.$id, $request->auction_image);
                $file_name = str_replace('auction/'.$id.'/', '', $file_path);
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            if (isset($request->auction_banner)) {
                $file_path = Storage::put('auction/banner/'.$id, $request->auction_banner);
                $file_name = str_replace('auction/banner/'.$id.'/', '', $file_path);
                $full_path = Storage::url($file_path);

                $payload['banner_file_name'] = $file_name;
                $payload['banner_file_path'] = $file_path;
                $payload['banner_full_path'] = $full_path;
            }

            // update auction
            $updated = $this->auctionRepository->update($id, $payload, true);
            // dd($updated);

            if ($updated) {
                $gap_error = DB::table('gap_errors')->whereNull('deleted_at')
                    ->where('reference_id', $id)
                    ->where('module', 'auction')
                    ->where('action', 'create')
                    ->first();
                // dd($gap_error);

                $auction = Auction::find($id);
                if ( $auction->sr_auction_id == null ) {
                    \Log::channel('gapLog')->info('call GAPAuctionCreateEvent');
                    event(new GAPAuctionCreateEvent($auction));
                }
                ## Comment Out this code (10Dec2020)
                //  else {
                //     \Log::channel('gapLog')->info('call GAPAuctionUpdateEvent');
                //     event(new GAPAuctionUpdateEvent($auction));
                // }

                ##command out by MCT (8Feb2022)
                // if ($old_end_date != $auction->timed_first_lot_ends && $auction->sr_auction_id != null && $auction->is_closed != 'Y') {
                //     ## Call CheckAuction job
                //     $datetime = new \Carbon\Carbon($auction->timed_first_lot_ends);
                //     \Log::channel('checkAuctionLog')->info('Auction update - dispatch CheckAuction '.$id);
                //     CheckAuction::dispatch($id)->delay($datetime->addMinutes(10));
                // }


                DB::commit();
                flash()->success(__(':name has been updated', ['name' => $auction->getTitle()]));
                return redirect(route('auction.auctions.show', ['auction' => $auction ]))->with('success', 'Auction Updated Successfully!');
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => ':name has not been updated', ['name' => Auction::find($id)->title]]));
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkDelete(Auction $auction)
    {
        try {
            $lots = AuctionItem::where('auction_id',$auction->id)->count();
            if($auction->is_published == 'Y' && $auction->is_closed != 'Y'){
                return response()->json([ 'status'=>'failed', 'message' => 'You cannot delete a live auction']);
            }
            if( ($auction->is_published != 'Y' || $auction->is_closed == 'Y') && $lots > 0){
                return response()->json([ 'status'=>'failed', 'message' => 'Please remove the lots from the auction first']);
            }
            if( ($auction->is_published != 'Y' || $auction->is_closed == 'Y') && $lots <= 0){
                $link = "https://toolbox.globalauctionplatform.com/auction";
                $message = "<p>Please ensure that you delete a corresponding auction in GAP Toolbox. <br><a class='btn btn-md btn-success mt-2' href='".$link."'>Go to Toolbox (".$auction->sr_reference.")</a></p>";
                return response()->json([ 'status'=>'success', 'message' => $message]);
            }

            return response()->json([ 'status'=>'failed', 'message' => 'You cannot delete this auction']);

        } catch (\Exception $e) {
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Auction $auction)
    {
        try {
            // check auction can destroy or not
            Storage::delete($auction->file_path);
            Storage::delete($auction->banner_file_path);
            $auction->delete();

            // flash()->success(__('Auction Deactivated Successfully'));
            return response()->json([ 'status'=>'success', 'message' => 'Auction '.$auction->title.' has been deleted']);

        } catch (\Exception $e) {
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
            // flash()->error(__('Auction Deactivated Failed'));
            // return redirect()->route('auction.auctions.index');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->auctionRepository->restore($id);
            DB::commit();

            return redirect()->route('auction.auctions.index')->with('success', 'Auction Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('auction.auctions.index')->with('fail', 'Auction Activating Failed!');
        }
    }

    public function publishAuction(Request $request)
    {
        try {
            $auction = Auction::find($request->id);

            \Log::channel('gapLog')->info('call GAPAuctionPublishEvent');
            event(new GAPAuctionPublishEvent($auction));

            return response()->json(array('status' => '1','message'=>'Auction Published Successfully.'));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function lotReorderList(Auction $auction)
    {
        // $auction_items = AuctionItem::whereNull('auction_items.deleted_at')
        //         ->where('auction_items.auction_id', $auction->id)
        //         ->orderBy('auction_items.sequence_number')
        //         ->orderBy('auction_items.lot_number')
        //         ->get();

        // $lots = [];
        // if (count($auction_items) > 0) {
        //     foreach ($auction_items as $key => $auction_item) {

        //         $photo = ItemImage::where('item_id', $auction_item->item_id)->select('file_name', 'full_path')->first();

        //         $itemlifecycle = ItemLifecycle::where('item_id',$auction_item->item_id)
        //                         ->where('reference_id',$auction->id)
        //                         ->where('type','auction')
        //                         ->first();

        //         $starting_bid = '0.00';
        //         if($itemlifecycle != null){
        //             $starting_bid = $itemlifecycle->price;
        //         }

        //         $itemDetail = Item::find($auction_item->item_id);

        //         $is_exist_in_first_auction = 'no';
        //         if($itemlifecycle != null && $itemDetail != null && in_array($itemDetail->status,[Item::_SWU_, Item::_PENDING_, Item::_PENDING_IN_AUCTION_]) ){

        //             $checkItemlifecycle = ItemLifecycle::where('item_id',$auction_item->item_id)
        //                         ->where('reference_id','!=',$auction->id)
        //                         ->where('id','<',$itemlifecycle->id)
        //                         ->where('type','auction')
        //                         ->first();

        //             if($checkItemlifecycle != null){
        //                 $first_auction = Auction::find($checkItemlifecycle->reference_id);
        //                 if($first_auction != null && $first_auction->is_closed != 'Y'){
        //                     $is_exist_in_first_auction = 'yes';
        //                 }
        //             }
        //         }

        //         if($itemDetail != null && !in_array($itemDetail->status,[Item::_IN_AUCTION_, Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_DISPATCHED_, Item::_DECLINED_]) && $is_exist_in_first_auction != 'yes'){

        //             $lots[] = [
        //                 'id' => $auction_item->id,
        //                 'item_id' => $auction_item->item_id,
        //                 'item_image' => ($photo)?$photo->full_path:'',
        //                 'item_name' => $itemDetail->name,
        //                 'low_estimate' => '$ '.number_format($itemDetail->low_estimate).' SGD',
        //                 'high_estimate' => '$ '.number_format($itemDetail->high_estimate).' SGD',
        //                 'starting_bid' => '$ '.number_format($starting_bid).' SGD',
        //             ];
        //         }
        //     }
        // }

        $reorder_lots = $this->auctionRepository->getPreAuctionItems($auction, 'lot_reorder');
        $data = [
            'lots' => $reorder_lots,
            'auction' => $auction,
        ];
        return view('auction::lot_reorder_list', $data);
    }

    public function lotReordering(Auction $auction, Request $request)
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['auction_item'] as $key2 => $auction_item_id) {
                $sequence_number = $key2 + 1;

                AuctionItem::where('id', $auction_item_id)->update(['lot_number'=>$sequence_number, 'sequence_number'=>$sequence_number] + NHelpers::updated_at_by());
            }
            DB::commit();

            ## command out this code for Lotreorder new logic [21Jan2021]
            // \Log::channel('lotReorderingLog')->info('Start - Call LotReorderEvent');
            // event( new LotReorderEvent($auction->id) );

            flash()->success(__('Lots are reordered successfully!'));
            return redirect()->route('auction.auctions.show', ['auction' => $auction ]);

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Lot Reordering Failed!']));
            return redirect()->route('auction.auctions.show', ['auction' => $auction ])->with('fail', 'Lot Reordering Failed!');
        }
    }

    public function generateLabel(Auction $auction)
    {
        $data = $this->auctionRepository->generateLabel($auction);

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
        $pdf = PDF::loadView('auction::pdf.generate_label_dom_two', $data);
        return $pdf->stream();

        // return view('auction::pdf.generate_label_dom', $data);
    }

    public function generateCatalogue(Auction $auction)
    {
        $data = $this->auctionRepository->generateCatalogue($auction);

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
        $pdf = PDF::loadView('auction::pdf.generate_catelogue_dom', $data);

        return $pdf->stream();

        // return view('auction::pdf.generate_catelogue', compact('data'));
    }

    public function generateBuyerLabel(Auction $auction)
    {
        $data = $this->auctionRepository->generateBuyerLabel($auction);

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
        $pdf = PDF::loadView('auction::pdf.generate_buyer_label_dom', $data);
        return $pdf->stream();

        // return view('auction::pdf.generate_buyer_label_dom', compact('data'));
    }

    public function generateSaleReport(Auction $auction)
    {
        $saleReports = $this->auctionRepository->generateSaleReport($auction, request()->seller, request()->status, true);

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

        $data = [
            'saleReports' => $saleReports,
            'logo' => $path_img
        ];

        view()->share('data', $data);
        $pdf = PDF::loadView('auction::pdf.generate_sale_report_dom', $data)->setPaper('a4', 'landscape');

        return $pdf->stream();
        // return view('auction::pdf.generate_sale_report', compact('saleReports'));
    }

    public function generateSellerReport(Auction $auction)
    {
        $sellerReports = $this->auctionRepository->generateSellerReport($auction, request()->seller, request()->status);
        // dd($sellerReports);

        $auction_results = isset($sellerReports['auction_results'])?$sellerReports['auction_results']:[];

        $notifications = isset($sellerReports['notifications'])?$sellerReports['notifications']:[];

        $seller = Customer::find(request()->seller);

        $data = [
            'subject' => $auction->title,
            'date' => date('l j F, Y'),
            'auction_results' => $auction_results,
            'notifications' => $notifications,
            'link' => url( config('app.url').route('my-bank', [], false) ),
            'logo' => asset('ecommerce/images/logo/est_logo.png'),
            'seller' => $seller
        ];

        view()->share('data', $data);
        return view('auction::pdf.generate_seller_report', $data);
    }

    public function search()
    {
        if(request()->closed == 'yes'){
            return Auction::where('is_closed', 'Y')->pluck('title');
        }else{
            return Auction::where('is_closed', '!=', 'Y')->pluck('title');
        }
    }

    public function createLotsIntoToolbox($id)
    {
        // dd($id);
        try {
            $auction = Auction::find($id);
            if($auction != null && $auction->sr_auction_id != null && $auction->is_closed != 'Y'){
                $no_permission_items = $this->auctionRepository->getNoPermissionItems($id);
                $count_of_no_permission_items = count($no_permission_items);

                if($count_of_no_permission_items > 0){
                    return response()->json([ 'status'=>'-2', 'message' => $count_of_no_permission_items.' items do not have permission to sell or cataloguing approve.']);
                }


                $gap_auction = Auction::getAuctionById($auction->sr_auction_id);
                if( isset($gap_auction['error']) ){
                    return response()->json([ 'status'=>'-1', 'message' => 'Auction is not exist in Toolbox']);
                }
                if( !isset($gap_auction['error']) ){
                    \Log::channel('lifecycleLog')->info('createLotsIntoToolbox '.$id);
                    $pure_auction_items = $this->auctionRepository->getAllPureAuctionItems($id);

                    foreach ($pure_auction_items as $key => $lot) {
                        $item = Item::find($lot->item_id);
                        if($item != null && $item->is_cataloguing_approved === 'Y' && $item->permission_to_sell === 'Y' && $item->status === Item::_PENDING_IN_AUCTION_){
                            \Log::info('Auction - dispatch ItemLifecycleInAuction Job '.$lot->item_id);
                            ItemLifecycleInAuction::dispatch($lot->item_id, $id);
                        }
                    }

                    return response()->json([ 'status'=>'success', 'message' => 'Auction '.$auction->title.' Lots have been created']);
                }
            }

            return response()->json([ 'status'=>'-1', 'message' => 'Auction is not exist in Hotlotz system']);

        } catch (\Exception $e) {
            return response()->json([ 'status'=>'-1', 'message' => $e->getMessage()]);
        }
    }

    public function showNoPermissionItems($id)
    {
        $no_permission_items = $this->auctionRepository->getNoPermissionItems($id);

        $data = [
            'no_permission_items'=>$no_permission_items,
        ];
        return view('auction::no_permission_item_list', $data);
    }

    public function getSaleReport(Auction $auction)
    {
        $saleReports = $this->auctionRepository->generateSaleReport($auction, request()->seller_id, request()->status);

        $returnHTML = view('auction::details.sale_report_table', [
            'saleReports' => $saleReports,
        ])->render();

        return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML));
    }

    public function publishToFrontend($id)
    {
        try {
            $auction = Auction::find($id);
            if($auction != null){
                $data = [
                    'publish_to_frontend' => 'Y',
                ];
                $this->auctionRepository->update($auction->id, $data, true);

                return response()->json([ 'status'=>'success', 'message' => 'Auction is published to fronted']);
            }

            return response()->json([ 'status'=>'failed', 'message' => 'Auction is not exist in Hotlotz system']);

        } catch (\Exception $e) {
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }

    public function unpublishToFrontend($id)
    {
        try {
            $auction = Auction::find($id);
            if($auction != null){
                $data = [
                    'publish_to_frontend' => 'N',
                ];
                $this->auctionRepository->update($auction->id, $data, true);

                return response()->json([ 'status'=>'success', 'message' => 'Auction is unpublished to fronted']);
            }

            return response()->json([ 'status'=>'failed', 'message' => 'Auction is not exist in Hotlotz system']);

        } catch (\Exception $e) {
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }

    public function getPreAuctionItemReport(Auction $auction)
    {
        // $auction_items = $this->auctionRepository->getPreAuctionItems($auction);
        // $total_lots = $this->auctionRepository->getPreAuctionItems($auction, 'total_count');
        // $total_starting_bid = $this->auctionRepository->getPreAuctionItems($auction, 'total_starting_bid');
        // $total_low_estimate = $this->auctionRepository->getPreAuctionItems($auction, 'total_low_estimate');
        // $total_high_estimate = $this->auctionRepository->getPreAuctionItems($auction, 'total_high_estimate');

        $pre_auction_data = $this->auctionRepository->getPreAuctionItems($auction, 'pre_auction_data');
        $auction_items = $pre_auction_data['preauction_items'];
        $total_lots = $pre_auction_data['total_count'];
        $total_starting_bid = $pre_auction_data['total_starting_bid'];
        $total_low_estimate = $pre_auction_data['total_low_estimate'];
        $total_high_estimate = $pre_auction_data['total_high_estimate'];

        $returnHTML = view('auction::details.pre_auction_item_table', [
            'auction_items' => $auction_items,
            'total_lots' => $total_lots,
            'total_starting_bid' => $total_starting_bid,
            'total_low_estimate' => $total_low_estimate,
            'total_high_estimate' => $total_high_estimate,
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    }

    public function getLotList(Auction $auction)
    {
        $lot_list = $this->auctionRepository->getLotsForClosedAuction($auction->id);

        $returnHTML = view('auction::details.lot_list_table', [
            'lot_list' => $lot_list,
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    }

    public function getTotalSettlement(Auction $auction)
    {
        $total_settlement = $this->auctionRepository->getAuctionTotalSettlement($auction);

        return response()->json(array('status' => 'success', 'data'=> number_format($total_settlement, 2, '.', '')));
    }

    public function getWinnerList(Auction $auction)
    {
        $winner_list = [];
        if ($auction->is_closed == 'Y' && isset($auction->sr_auction_id)) {
            $gap_winner_list = Auction::getWinnersByAuctionId($auction->sr_auction_id);
            if (!isset($gap_winner_list['error'])) {
                $winner_list = $gap_winner_list;
            }
        }

        $returnHTML = view('auction::details.winner_list', [
            'winner_list' => $winner_list,
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    }

    public function getBidderList(Auction $auction)
    {
        $bidder_list = [];
        if ($auction->is_closed == 'Y' && isset($auction->sr_auction_id)) {
            $gap_bidder_list = Auction::getBiddersByAuctionId($auction->sr_auction_id);
            if (!isset($gap_bidder_list['error'])) {
                $bidder_list = $gap_bidder_list;
            }
        }

        $returnHTML = view('auction::details.bidder_list', [
            'bidder_list' => $bidder_list,
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    }


    public function sendKycIndividualSellerEmails(Auction $auction)
    {
        $item_ids = AuctionItem::where('auction_id',$auction->id)->pluck('item_id')->all();
        \Log::channel('emailLog')->info('sendKycIndividualSellerEmails item count : '.count($item_ids) );

        if(count($item_ids) > 0){
            $customer_ids = Item::whereIn('items.id',$item_ids)
                    ->join('customers', 'customers.id', 'items.customer_id')
                    ->where('customers.type', 'individual')
                    ->where('customers.is_kyc_approved', '!=', 'Y')
                    ->select('items.customer_id')
                    ->groupBy('customer_id')
                    ->pluck('customer_id')->all();
            \Log::channel('emailLog')->info('sendKycIndividualSellerEmails Sellers count : '.count($customer_ids) );
            \Log::channel('emailLog')->info('sendKycIndividualSellerEmails seller_id : '.print_r($customer_ids,true) );

            foreach ($customer_ids as $key => $seller_id) {
                \Log::channel('emailLog')->info('sendKycIndividualSellerEmails : called SendKycIndividualSellerEmailEvent for Seller_'. $seller_id);
                event(new SendKycIndividualSellerEmailEvent($seller_id));
            }
        }
        flash()->success(__('Great! Successfully send in KYC Individual Seller mails'));
        return redirect(route('auction.auctions.show', $auction))->with('success', 'Great! Successfully send in KYC Individual Seller mails');
    }

    public function sendKycCompanySellerEmails(Auction $auction)
    {
        $item_ids = AuctionItem::where('auction_id',$auction->id)->pluck('item_id')->all();
        \Log::channel('emailLog')->info('sendKycCompanySellerEmails item count : '.count($item_ids) );

        if(count($item_ids) > 0){
            $customer_ids = Item::whereIn('items.id',$item_ids)
                    ->join('customers', 'customers.id', 'items.customer_id')
                    ->where('customers.type', 'organization')
                    ->where('customers.is_kyc_approved', '!=', 'Y')
                    ->select('items.customer_id')
                    ->groupBy('customer_id')
                    ->pluck('customer_id')->all();
            \Log::channel('emailLog')->info('sendKycCompanySellerEmails Sellers count : '.count($customer_ids) );
            \Log::channel('emailLog')->info('sendKycCompanySellerEmails seller_id : '.print_r($customer_ids,true) );

            foreach ($customer_ids as $key => $seller_id) {
                \Log::channel('emailLog')->info('sendKycCompanySellerEmails : called SendKycCompanySellerEmailEvent for Seller_'. $seller_id);
                event(new SendKycCompanySellerEmailEvent($seller_id));
            }
        }
        flash()->success(__('Great! Successfully send in KYC Company Seller mails'));
        return redirect(route('auction.auctions.show', $auction))->with('success', 'Great! Successfully send in KYC Company Seller mails');
    }

    public function sendKycBuyerEmails(Auction $auction)
    {
        $item_ids = AuctionItem::where('auction_id',$auction->id)->where('sold_price','>=','20000')->pluck('item_id')->all();
        \Log::channel('emailLog')->info('sendKycBuyerEmails item count : '.count($item_ids) );

        if(count($item_ids) > 0){
            $buyer_ids = Item::whereIn('id',$item_ids)->groupBy('buyer_id')->pluck('buyer_id')->all();
            \Log::channel('emailLog')->info('sendKycBuyerEmails Buyers count : '.count($buyer_ids) );
            \Log::channel('emailLog')->info('sendKycBuyerEmails buyer_ids : '.print_r($buyer_ids,true) );

            foreach ($buyer_ids as $key => $buyer_id) {
                \Log::channel('emailLog')->info('sendKycBuyerEmails : called SendKycBuyerEmailEvent for Buyer_'. $buyer_id);
                event(new SendKycBuyerEmailEvent($buyer_id));
            }
        }
        flash()->success(__('Great! Successfully send in KYC Buyer mails'));
        return redirect(route('auction.auctions.show', $auction))->with('success', 'Great! Successfully send in KYC Buyer mails');
    }

    public function generateKycReport(Auction $auction)
    {
        $kycReports = $this->auctionRepository->generateKycReport($auction);

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

        $data = [
            'kycReports' => $kycReports,
            'logo' => $path_img
        ];

        view()->share('data',$data);
        $pdf = PDF::loadView('auction::pdf.generate_kyc_report', $data);

        return $pdf->stream();

        return view('auction::pdf.generate_kyc_report', compact('data'));
    }

    public function lifecycleResetForAllItems(Auction $auction)
    {
        $auction_id = $auction->id;
        $item_ids = AuctionItem::where('auction_items.auction_id',$auction_id)
                    ->whereNull('auction_items.lot_id')
                    ->join('items','items.id','auction_items.item_id')
                    ->whereNull('items.deleted_at')
                    ->where('items.status', Item::_PENDING_)
                    ->where('items.is_cataloguing_approved', 'Y')
                    ->where('items.permission_to_sell', 'Y')
                    ->pluck('auction_items.item_id')
                    ->all();
        // dd($item_ids);

        if(count($item_ids) > 0){
            foreach ($item_ids as $key => $item_id) {
                $item = Item::find($item_id);
                if($item && isset($item) && $item != null && !is_null($item) && !empty($item) && $item->is_cataloguing_approved === 'Y' && $item->permission_to_sell === 'Y' && $item->status === Item::_PENDING_){
                
                    \Log::info('Lifecycle Reset by Auction - dispatch LifecycleStart Job '.$item_id);
                    LifecycleStart::dispatch($item_id);
                }
            }
        }

        return response()->json(array('status' => 'success', 'message'=>'Great! Successfully Lifecycle Reset!'));
    }
}
