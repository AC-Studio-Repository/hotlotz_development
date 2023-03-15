<?php

namespace App\Modules\AuctionMainPage\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\AuctionMainPage\Http\Repositories\AuctionResultsMainRepository;
use App\Modules\AuctionMainPage\Http\Repositories\PastCataloguesMainRepository;
use App\Modules\AuctionMainPage\Models\AuctionResultsMain;
use App\Modules\AuctionMainPage\Models\PastCataloguesMain;
use DB;
use Response;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;

class AuctionMainPageController extends BaseController
{
    protected $auctionReultsMainPageRepository;
    protected $pastCataloguesMainPageRepository;
    public function __construct(AuctionResultsMainRepository $auctionReultsMainPageRepository, PastCataloguesMainRepository $pastCataloguesMainPageRepository){
        $this->auctionReultsMainPageRepository = $auctionReultsMainPageRepository;
        $this->pastCataloguesMainPageRepository = $pastCataloguesMainPageRepository;

        $auction_results_upload_dir = storage_path('app/public/auction_results_info/');
        NHelpers::dir_exists($auction_results_upload_dir);
        $this->auction_results_upload_dir = $auction_results_upload_dir;
        $past_catalogues_upload_dir = storage_path('app/public/past_catalogues_info/');
        NHelpers::dir_exists($past_catalogues_upload_dir);
        $this->past_catalogues_upload_dir = $past_catalogues_upload_dir; 
    }

    public function index()
    {
       
    }

    public function auctionResultsIndex()
    {
        $auction_results_data = AuctionResultsMain::all();
        $auction_results = [];
        $banner = '';

        if (!$auction_results_data->isEmpty()){
            $auction_results = $auction_results_data->first();
            $banner = $auction_results->banner_image;
        }

        $data = [
                'auction_results_data' => $auction_results_data,
                'auction_results' => $auction_results,
                'banner' => $banner
            ];

        return view('auction_main_page::auction_results_index',$data);
    }

    public function editAuctionResultsContent()
    {
        $auction_results_data = AuctionResultsMain::all();
        $auction_results = [];
        $banner = '';

        if (!$auction_results_data->isEmpty()){
            $auction_results = $auction_results_data->first();
            $banner = $auction_results->banner_image;
        }


        $data = [
                'auction_results_data' => $auction_results_data,
                'auction_results' => $auction_results,
                'hide_auction_results_image_ids' => '',
                'banner' => $banner
            ];

        return view('auction_main_page::auction_results_edit',$data);
    }

    public function bannerAuctionResultsUpload(Request $request)
    {
        try{
            if ($auction_results_banner_image = $request->file('auction_results_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($auction_results_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/auction_results_info', array($auction_results_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $auction_results_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $auction_results_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$auction_results_banner_image_path,
                    'ids'=>$images_ids,
                    'initialPreview' => $p1, 
                    'initialPreviewConfig' => $p2,   
                    'append' => true
                ];

                return json_encode($data);
        } catch (Exception $e) {
            return json_encode(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function updateAuctionResultsContent(Request $request)
    {
        $auction_results_data = AuctionResultsMain::all();

        $action = '';
        if (!$auction_results_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packAuctionResultsData($request);

            if($action == 'save') {
                $result = AuctionResultsMain::create($payload);
            }else{
                $this->auctionReultsMainPageRepository->update(1, $payload, true);
            }
            DB::commit();
           
           return response()->json(array('status' => '1','message'=>'Update Auction Result Main Successfully.')); 
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    protected function packAuctionResultsData($request)
    {
        $payload['banner_image'] = $request->banner_image;
        $payload['caption'] = $request->caption;
         $payload['title_header'] = $request->title_header;
        $payload['title_blog'] = $request->title_blog;
       
        return $payload;
    }

    public function pastCataloguesIndex()
    {
        $past_catalogues_data = PastCataloguesMain::all();
        $past_catalogues = [];
        $banner = '';

        if (!$past_catalogues_data->isEmpty()){
            $past_catalogues = $past_catalogues_data->first();
            $banner = $past_catalogues->banner_image;
        }

        $data = [
                'past_catalogues_data' => $past_catalogues_data,
                'past_catalogues' => $past_catalogues,
                'banner' => $banner
            ];

        return view('auction_main_page::past_catalogues_index',$data);
    }

    public function editPastCataloguesContent()
    {
        $past_catalogues_data = PastCataloguesMain::all();
        $past_catalogues = [];
        $banner = '';

        if (!$past_catalogues_data->isEmpty()){
            $past_catalogues = $past_catalogues_data->first();
            $banner = $past_catalogues->banner_image;
        }

        $data = [
                'past_catalogues_data' => $past_catalogues_data,
                'past_catalogues' => $past_catalogues,
                'hide_past_catalogues_image_ids' => '',
                'banner' => $banner
            ];

        return view('auction_main_page::past_catalogues_edit',$data);
    }

    public function bannerPastCataloguesUpload(Request $request)
    {
        try{
            if ($past_catalogues_banner_image = $request->file('past_catalogues_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($past_catalogues_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/past_catalogues_info', array($past_catalogues_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $past_catalogues_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $past_catalogues_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$past_catalogues_banner_image_path,
                    'ids'=>$images_ids,
                    'initialPreview' => $p1, 
                    'initialPreviewConfig' => $p2,   
                    'append' => true
                ];

                return json_encode($data);
        } catch (Exception $e) {
            return json_encode(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function updatePastCataloguesContent(Request $request)
    {
        $auction_results_data = PastCataloguesMain::all();

        $action = '';
        if (!$auction_results_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packPastCataloguesData($request);

            if($action == 'save') {
                $result = PastCataloguesMain::create($payload);
            }else{
                $this->pastCataloguesMainPageRepository->update(1, $payload, true);
            }
            DB::commit();
           
           return response()->json(array('status' => '1','message'=>'Update Past Catalogues Main Successfully.')); 
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    protected function packPastCataloguesData($request)
    {
        $payload['banner_image'] = $request->banner_image;
        $payload['caption'] = $request->caption;
        $payload['title_header'] = $request->title_header;
        $payload['title_blog'] = $request->title_blog;
       
        return $payload;
    }
}
