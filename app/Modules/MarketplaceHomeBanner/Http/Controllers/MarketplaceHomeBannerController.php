<?php

namespace App\Modules\MarketplaceHomeBanner\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use DB;
use Response;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;

use App\Modules\MarketplaceHomeBanner\Http\Requests\StoreMarketplaceHomeBannerRequest;
use App\Modules\MarketplaceHomeBanner\Http\Requests\UpdateMarketplaceHomeBannerRequest;

use App\Modules\MarketplaceHomeBanner\Http\Repositories\MarketplaceHomeBannerRepository;
use App\Modules\MarketplaceHomeBanner\Models\MarketplaceHomeBanner;

class MarketplaceHomeBannerController extends BaseController
{
    protected $marketplaceHomeBannerRepository;

    public function __construct(MarketplaceHomeBannerRepository $marketplaceHomeBannerRepository){
        $this->marketplaceHomeBannerRepository = $marketplaceHomeBannerRepository;
    }

    public function index()
    {
        $status = '';
        $banner_count = 1;
        $main_banners = MarketplaceHomeBanner::all();
        $previewImages = [];
        $latest_id = 0;
        $header_title = '';

        if(!$main_banners->isEmpty())
        {
            $status = 'edit';
            $banner_count = $main_banners->count();
            // $latest_id = MarketplaceHomeBannerBanner::latest_id()->first()->id;
            $latest_data = DB::table('marketplace_home_banners')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            $header_title = $latest_data->header_title;
            foreach($main_banners as $banner)
            {
                $previewImages[] = [
                    'id'  => $banner->id,
                    'previewImage' =>  $banner->file_path
                   ]; 
            }
        }else{
            $status = 'create';
        }
       // dd($previewImages);
        $data = [
            'main_banners' => $main_banners,
            'banner_count' => $banner_count,
            'status' => $status,
            'previewImages' => $previewImages,
            'latest_id' => $latest_id,
            'header_title' => $header_title
        ];

        return view('marketplace_home_banner::index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInfo(StoreMarketplaceHomeBannerRequest $request)
    {
        DB::beginTransaction();
        try {
            $marketplace_home_banner = app(MarketplaceHomeBanner::class);

            $item_count = $request->hid_item_count;
            $header_title = $request->header_title;
            $payload['header_title'] = $header_title;

            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {
                if(isset($request['caption_'.$i])){
                
                    $payload['caption'] = $request['caption_'.$i];
                    $payload['sub_title'] = $request['sub_title_'.$i];
                    $payload['banner'] = $request['hide_banner_'.$i];
                    $payload['file_path'] = $request['hide_filepath_banner_'.$i];
                    $payload['learn_more'] = $request['learn_more_'.$i];
                    $hid_edit_id = (int)$request['hid_edit_id_'.$i];
                    $hid_delete_id = (int)$request['hid_delete_id_'.$i];
                
                    if($hid_edit_id == 0 && $hid_delete_id == 0)
                    {
                        //create
                        $MarketplaceHomeBanner = MarketplaceHomeBanner::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        $banner_data = MarketplaceHomeBanner::all();
                        if($banner_data->count() > 1)
                        {
                            //truncate
                            $this->marketplaceHomeBannerRepository->destroy($hid_delete_id);
                            DB::commit();
                        }else{
                            //delete
                           MarketplaceHomeBanner::truncate();
                        }
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->marketplaceHomeBannerRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }      
            }

            flash()->success(__('Marketplace Main Banner has been Modified'));
            return redirect(route('marketplace_home_banner.marketplace_home_banners.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    public function marketplace_home_banner_upload(Request $request)
    {
        // dd($request->all());
        try{
            if ($marketplace_home_banner_image = $request->file($request->banner_name)) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $banner_count = $request->banner_count;
                $banner_name = $request->banner_name;

                    if(isset($marketplace_home_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/marketplace_home_banners/'.$banner_count, array($marketplace_home_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $marketplace_home_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $marketplace_home_banner_image_path,
                        ];
                    }
                }

                $data = [
                    'status'=>1,
                    'saved_filepath'=>$filename,
                    'saved_storage_filepath'=>$marketplace_home_banner_image_path,
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
}
