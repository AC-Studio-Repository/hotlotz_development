<?php

namespace App\Modules\HomePage\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\HomePage\Http\Requests\StoreHomePageRequest;
use App\Modules\HomePage\Http\Requests\UpdateHomePageRequest;
use App\Modules\HomePage\Http\Requests\AjaxCreateContentManagement;
use App\Modules\HomePage\Http\Repositories\HomePageRepository;
use App\Modules\HomePage\Http\Repositories\HomePageMarketplaceRepository;
use App\Modules\HomePage\Models\HomePage;
use DB;
use Response;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use App\Models\GeneralInfo;
use App\Modules\HomePage\Models\HomePageTestimonial;
use App\Modules\Testimonial\Models\Testimonial;
use App\Modules\HomePage\Models\HomePageBanner;
use App\Modules\HomePage\Models\HomePageMarketplaceBanner;


class HomePageController extends BaseController
{
    protected $HomePageRepository;
    protected $HomePageMarketplaceRepository;

    public function __construct(HomePageRepository $HomePageRepository, HomePageMarketplaceRepository $HomePageMarketplaceRepository){
        $this->HomePageRepository = $HomePageRepository;
        $this->HomePageMarketplaceRepository = $HomePageMarketplaceRepository;
    }

    public function index()
    {
        return view('home_page::showlist');
    }

    public function main_banner_index()
    {
        $status = '';
        $banner_count = 1;
        $main_banners = HomePageBanner::all();
        $previewImages = [];
        $latest_id = 0;

        if(!$main_banners->isEmpty())
        {
            $status = 'edit';
            $banner_count = $main_banners->count();
            // $latest_id = HomePageBanner::latest_id()->first()->id;
            $latest_data = DB::table('homepage_banners')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
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
            'latest_id' => $latest_id
        ];

        return view('home_page::main_banner_index',$data);
    }

    public function main_banner_list()
    {
        return view('home_page::mainBannerList');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInfo(StoreHomePageRequest $request)
    {
        DB::beginTransaction();
        try {
            $home_page = app(HomePage::class);

            $item_count = $request->hid_item_count;
            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {
                if(isset($request['caption_'.$i])){
                    $payload['main_title'] = $request['main_title_'.$i];
                    $payload['caption'] = $request['caption_'.$i];
                    $payload['banner'] = $request['hide_banner_'.$i];
                    $payload['file_path'] = $request['hide_filepath_banner_'.$i];
                    $payload['position'] = $request['position_'.$i];
                    $payload['color'] = $request['color_'.$i];
                    $payload['link_name'] = $request['link_name_'.$i];
                    $payload['link'] = $request['link_'.$i];
                    $payload['type'] = 'main';
                    $hid_edit_id = (int)$request['hid_edit_id_'.$i];
                    $hid_delete_id = (int)$request['hid_delete_id_'.$i];

                    if($hid_edit_id == 0 && $hid_delete_id == 0)
                    {
                        $homepage = HomePageBanner::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        $banner_data = HomePageBanner::all();
                        if($banner_data->count() > 1)
                        {
                            $this->HomePageRepository->destroy($hid_delete_id);
                            DB::commit();
                        }else{
                           HomePageBanner::truncate();
                        }
                    }
                    else if($hid_edit_id  > 0)
                    {
                        $this->HomePageRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }

            flash()->success(__('Main Banner has been Modified'));
            return redirect(route('home_page.home_pages.main_banner_list'));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    public function homepage_main_banner_upload(Request $request)
    {
        try{
            if ($home_page_image = $request->file($request->banner_name)) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $banner_count = $request->banner_count;
                $banner_name = $request->banner_name;

                    if(isset($home_page_image))
                    {
                        $result = StorageHelper::store($path = 'public/homepage_banners/main/'.$banner_count, array($home_page_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $home_page_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $home_page_image_path,
                        ];
                    }
                }

                $data = [
                    'status'=>1,
                    'saved_filepath'=>$filename,
                    'saved_storage_filepath'=>$home_page_image_path,
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

    public function marketplace_banner_index()
    {
        $status = '';
        $banner_count = 1;
        $main_banners = HomePageMarketplaceBanner::all();
        $previewImages = [];
        $latest_id = 0;

        if(!$main_banners->isEmpty())
        {
            $status = 'edit';
            $banner_count = $main_banners->count();
            // $latest_id = HomePageMarketplaceBanner::latest_id()->first()->id;
            $latest_data = DB::table('homepage_marketplace_banners')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
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

        $data = [
            'main_banners' => $main_banners,
            'banner_count' => $banner_count,
            'status' => $status,
            'previewImages' => $previewImages,
            'latest_id' => $latest_id
        ];

        return view('home_page::marketplace_banner_index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMarketplaceBanner(StoreHomePageRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $home_page = app(HomePage::class);

            $item_count = $request->hid_item_count;
            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {
                if(isset($request['hide_filepath_banner_'.$i]))
                {
                    // $payload['caption'] = $request['caption_'.$i];
                    $payload['banner'] = $request['hide_banner_'.$i];
                    $payload['file_path'] = $request['hide_filepath_banner_'.$i];
                    $payload['type'] = 'marketplace';
                    $hid_edit_id = (int)$request['hid_edit_id_'.$i];
                    $hid_delete_id = (int)$request['hid_delete_id_'.$i];

                    if($hid_edit_id == 0 && $hid_delete_id == 0)
                    {
                        //create
                        $homepage = HomePageMarketplaceBanner::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $banner_data = HomePageMarketplaceBanner::all();
                        if($banner_data->count() > 1)
                        {
                            $this->HomePageMarketplaceRepository->destroy($hid_delete_id);
                            DB::commit();
                        }else{
                            HomePageMarketplaceBanner::truncate();
                        }
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->HomePageMarketplaceRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }

            flash()->success(__('Marketplace Banner has been Modified'));
            return redirect(route('home_page.home_pages.main_banner_list'));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    public function homepage_marketplace_banner_upload(Request $request)
    {
        try{
            if ($home_page_image = $request->file($request->banner_name)) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $banner_count = $request->banner_count;
                $banner_name = $request->banner_name;

                    if(isset($home_page_image))
                    {
                        $result = StorageHelper::store($path = 'public/homepage_banners/marketplace/'.$banner_count, array($home_page_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $home_page_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $home_page_image_path,
                        ];
                    }
                }

                $data = [
                    'status'=>1,
                    'saved_filepath'=>$filename,
                    'saved_storage_filepath'=>$home_page_image_path,
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

    public function showtestimonial()
    {
        $testimonial = Testimonial::all();

        $homepage_testimonial = HomePageTestimonial::pluck('testimonial_id')->all();
        $data = [
            'testimonial' => $testimonial,
            'homepage_testimonial' => $homepage_testimonial
        ];

        return view('home_page::showtestimonial',$data);
    }

    public function storeTestimonialAjax(Request $request)
    {
        $selected_testimonial_data = json_decode($request->selected_testimonial);

        DB::beginTransaction();
        try {
            HomePageTestimonial::truncate();

            $count = 0;
            foreach($selected_testimonial_data as $row)
            {
                $count++;
                $payload['testimonial_id'] = (integer)$row;
                if($count == 1){
                    $payload['status'] = 'active';
                }

                $result = HomePageTestimonial::create($payload);
                if($result){
                    DB::commit();
                }else{
                    DB::rollback();
                }
            }
            return response()->json(array('status' => '1','message'=>'Create Content Successfully.'));
        } catch (\Exception $e) {
            DB::rollback();
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                return redirect()->back()->withInput();
        }
    }
}
