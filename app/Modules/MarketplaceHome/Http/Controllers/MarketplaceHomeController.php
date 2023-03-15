<?php

namespace App\Modules\MarketplaceHome\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use DB;
use Response;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;

use App\Modules\MarketplaceHome\Models\MarketplaceHomeSubstainableSourcing;
use App\Modules\MarketplaceHome\Models\MarketplaceCollaboration;
use App\Modules\MarketplaceHome\Models\MarketplaceCollaborationBlog;
use App\Modules\MarketplaceHome\Models\MarketplaceCollabrationInfo;
use App\Modules\MarketplaceHome\Models\MarketplaceItemDetailPolicy;

use App\Modules\MarketplaceHome\Http\Repositories\MarketplaceHomeSubstainableSourcingRepository;
use App\Modules\MarketplaceHome\Http\Repositories\MarketplaceCollaborationRepository;
use App\Modules\MarketplaceHome\Http\Repositories\MarketplaceCollaborationBlogRepository;
use App\Modules\MarketplaceHome\Http\Repositories\MarketplaceCollabrationInfoRepository;
use App\Modules\MarketplaceHome\Http\Repositories\MarketplaceItemDetailPolicyRepository;

class MarketplaceHomeController extends BaseController
{
    protected $marketplaceHomeSustainableSourcingRepository;
    protected $marketplaceCollaborationRepository;
    protected $marketplaceCollaborationBlogRepository;
    protected $marketplaceCollabrationInfoRepository;
    protected $marketplaceItemDetailPolicyRepository;

    public function __construct(MarketplaceHomeSubstainableSourcingRepository $marketplaceHomeSustainableSourcingRepository, MarketplaceCollaborationRepository $marketplaceCollaborationRepository, MarketplaceCollaborationBlogRepository $marketplaceCollaborationBlogRepository,
        MarketplaceCollabrationInfoRepository $marketplaceCollabrationInfoRepository,
        MarketplaceItemDetailPolicyRepository $marketplaceItemDetailPolicyRepository){
        $this->marketplaceHomeSubstainableSourcingRepository = $marketplaceHomeSustainableSourcingRepository;
        $this->marketplaceCollaborationRepository = $marketplaceCollaborationRepository;
        $this->marketplaceCollaborationBlogRepository = $marketplaceCollaborationBlogRepository;
        $this->marketplaceCollabrationInfoRepository = $marketplaceCollabrationInfoRepository;
        $this->marketplaceItemDetailPolicyRepository = $marketplaceItemDetailPolicyRepository;
    }

    public function index()
    {
        return view('marketplace_home::mainBannerList');
    }

    public function collaboration_list()
    {
        return view('marketplace_home::collaboration_list');
    }

    public function substainable_banner_index()
    {
        $status = '';
        $banner_count = 1;
        $main_banners = MarketplaceHomeSubstainableSourcing::all();
        $previewImages = [];
        $latest_id = 0;

        if(!$main_banners->isEmpty())
        {
            $status = 'edit';
            $banner_count = $main_banners->count();

            $latest_data = DB::table('marketplace_sustainable_sourcing_banners')->orderBy('id', 'DESC')->first();
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

        return view('marketplace_home::substainable_banner_index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_substainable_Info(Request $request)
    {
        DB::beginTransaction();
        try {
            $home_page = app(MarketplaceHomeSubstainableSourcing::class);

            $item_count = $request->hid_item_count;
            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {
                if(isset($request['link_'.$i])){

                    $payload['header_title'] = $request['header_title_'.$i];
                    $payload['header_description'] = $request['header_description_'.$i];
                    $payload['link'] = $request['link_'.$i];
                    $payload['banner'] = $request['hide_banner_'.$i];
                    $payload['file_path'] = $request['hide_filepath_banner_'.$i];
                    $hid_edit_id = (int)$request['hid_edit_id_'.$i];
                    $hid_delete_id = (int)$request['hid_delete_id_'.$i];

                    if($hid_edit_id == 0 && $hid_delete_id == 0)
                    {
                        //create
                        $MarketplaceHome = MarketplaceHomeSubstainableSourcing::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        $banner_data = MarketplaceHomeSubstainableSourcing::all();
                        if($banner_data->count() > 1)
                        {
                            //truncate
                            $this->marketplaceHomeSubstainableSourcingRepository->destroy($hid_delete_id);
                            DB::commit();
                        }else{
                            //delete
                           MarketplaceHomeSubstainableSourcing::truncate();
                        }
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->marketplaceHomeSubstainableSourcingRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }

            flash()->success(__('Sustainable Sourcing Banner has been Modified'));
            return redirect(route("marketplace_home.marketplace_homes.index"));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    public function sustainable_sorcing_banner_upload(Request $request)
    {
        try{
            if ($banner_image = $request->file($request->banner_name)) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $banner_count = $request->banner_count;
                $banner_name = $request->banner_name;

                    if(isset($banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/substainable_banners/'.$banner_count, array($banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $banner_image_path,
                        ];
                    }
                }

                $data = [
                    'status'=>1,
                    'saved_filepath'=>$filename,
                    'saved_storage_filepath'=>$banner_image_path,
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

    public function collaboration_banner_index()
    {
        $status = '';
        $banner_count = 1;
        $main_banners = MarketplaceCollaboration::all();
        $previewImages = [];
        $latest_id = 0;

        if(!$main_banners->isEmpty())
        {
            $status = 'edit';
            $banner_count = $main_banners->count();

            $latest_data = DB::table('marketplace_collaboration_banners')->orderBy('id', 'DESC')->first();
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

        return view('marketplace_home::collaboration_banner_index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_collaboration_info(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $home_page = app(MarketplaceCollaboration::class);

            $item_count = $request->hid_item_count;
            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {

                if(isset($request['header_title_'.$i]))
                {
                    $payload['header_title'] = $request['header_title_'.$i];
                    $payload['slogan'] = $request['slogan_'.$i];
                    $payload['banner'] = $request['hide_banner_'.$i];
                    $payload['file_path'] = $request['hide_filepath_banner_'.$i];
                    $hid_edit_id = (int)$request['hid_edit_id_'.$i];
                    $hid_delete_id = (int)$request['hid_delete_id_'.$i];

                    if($hid_edit_id == 0 && $hid_delete_id == 0)
                    {
                        // echo 'creaet';
                        //create
                        $MarketplaceHome = MarketplaceCollaboration::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        $banner_data = MarketplaceCollaboration::all();
                        if($banner_data->count() > 1)
                        {
                            $this->marketplaceCollaborationRepository->destroy($hid_delete_id);
                            DB::commit();
                        }else{
                            MarketplaceCollaboration::truncate();
                        }
                    }
                    else if($hid_edit_id  > 0)
                    {
                        $this->marketplaceCollaborationRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }

            }

            flash()->success(__('Collaboration Banner has been Modified'));
            return redirect(route("marketplace_home.marketplace_homes.index"));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    public function collaboration_banner_upload(Request $request)
    {
        try{
            if ($banner_image = $request->file($request->banner_name)) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $banner_count = $request->banner_count;
                $banner_name = $request->banner_name;

                    if(isset($banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/collaboration_banners/'.$banner_count, array($banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $banner_image_path,
                        ];
                    }
                }

                $data = [
                    'status'=>1,
                    'saved_filepath'=>$filename,
                    'saved_storage_filepath'=>$banner_image_path,
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

    public function editcontent()
    {
        $marketpalce_info_data = MarketplaceCollabrationInfo::all();
        $marketpalce_info = [];
        $banner = '';
        $blog_count = 0;
        $blogs = MarketplaceCollaborationBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$marketpalce_info_data->isEmpty()){
            $marketpalce_info = $marketpalce_info_data->first();
            $banner = $marketpalce_info->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();

            $latest_data = DB::table('marketplace_collaboration_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'marketpalce_info_data' => $marketpalce_info_data,
                'marketpalce_info' => $marketpalce_info,
                'hide_marketplace_info_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count
            ];

        return view('marketplace_home::edit',$data);
    }

    public function updateContent(Request $request)
    {
        $marketplace_info_data = MarketplaceCollabrationInfo::all();

        $action = '';
        if (!$marketplace_info_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = MarketplaceCollabrationInfo::create($payload);
            }else{
                $this->marketplaceCollabrationInfoRepository->update(1, $payload, true);
            }
            DB::commit();

            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {
                if(isset($request['title_'.$i]))
                {
                    $payload['title'] = $request['title_'.$i];
                    $payload['blog'] = $request['blog_'.$i];
                    $hid_edit_id = (int)$request['hid_edit_id_'.$i];
                    $hid_delete_id = (int)$request['hid_delete_id_'.$i];

                    if($hid_edit_id == 0 && $hid_delete_id == 0)
                    {
                        //create
                        $marketplae_collaboration_blog = MarketplaceCollaborationBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->marketplaceCollaborationBlogRepository->destroy($hid_delete_id);
                        DB::commit();

                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->marketplaceCollaborationBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }

           flash()->success(__('Update Marketplace Collabration Blog Successfully'));
            return redirect(route("marketplace_home.marketplace_homes.index"));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_marketplace_info_image_ids;
        $payload['caption'] = $request->caption;
        $payload['image_properties'] = $request->hide_marketplace_image_property;
       
        return $payload;
    }

    public function info_banner_upload(Request $request)
    {
        try{
            if ($marketplace_info_banner = $request->file('marketplace_info_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($marketplace_info_banner))
                    {
                        $orignal_name = $marketplace_info_banner->getClientOriginalName();
                        $original_extension = $marketplace_info_banner->getClientOriginalExtension();
                        $size = $marketplace_info_banner->getSize();
                        $type = $marketplace_info_banner->getMimeType();
                        $image_properties = $orignal_name.','.$original_extension.','.$size.','.$type;

                        $result = StorageHelper::store($path = 'public/collboration_page/info', array($marketplace_info_banner), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $marketplace_info_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $marketplace_info_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$marketplace_info_image_path,
                    'saved_property'=>$image_properties,
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

    public function detail_item_policy()
    {
        $policy_data = MarketplaceItemDetailPolicy::all();
        $policy = [];

        if (!$policy_data->isEmpty()){
            $policy = $policy_data->first();
        }

        $data = [
                'policy_data' => $policy_data,
                'policy' => $policy
            ];

        return view('marketplace_home::detail_item_policy',$data);
    }

    public function updatePolicyContent(Request $request)
    {
        $policy_data = MarketplaceItemDetailPolicy::all();

        $action = '';
        if (!$policy_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packPolicyData($request);

            if($action == 'save') {
                $result = MarketplaceItemDetailPolicy::create($payload);
            }else{
                $this->marketplaceItemDetailPolicyRepository->update(1, $payload, true);
            }
            DB::commit();
           
           flash()->success(__('Update Item Detail Policy Successfully'));
            return redirect(route('marketplace_home.marketplace_homes.index')); 
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packPolicyData($request)
    {
        $payload['collection_Shipping_header'] = $request->collection_Shipping_header;
        $payload['collection_Shipping_blog'] = $request->collection_Shipping_blog;

        $payload['one_tree_planted_header'] = $request->one_tree_planted_header;
        $payload['one_tree_planted_blog'] = $request->one_tree_planted_blog;

        $payload['sale_policy_header'] = $request->sale_policy_header;
        $payload['sale_policy_blog'] = $request->sale_policy_blog;
       
        return $payload;
    }
}
