<?php

namespace App\Modules\BusinessSeller\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\BusinessSeller\Models\BusinessSeller;
use App\Modules\BusinessSeller\Models\BusinessSellerBlog;
use App\Modules\OurTeam\Models\OurTeam;

use App\Modules\BusinessSeller\Http\Repositories\BusinessSellerRepository;
use App\Modules\BusinessSeller\Http\Repositories\BusinessSellerBlogRepository;

class BusinessSellerController extends BaseController
{
    protected $businessSellerRepository;
    protected $businessSellerBlogRepository;
    public function __construct(BusinessSellerRepository $businessSellerRepository, BusinessSellerBlogRepository $businessSellerBlogRepository){
        $this->businessSellerRepository = $businessSellerRepository;
        $this->businessSellerBlogRepository = $businessSellerBlogRepository;
    }

    public function index()
    {
        $business_seller_data = BusinessSeller::all();
        $business_seller = [];
        $banner = '';
        $blogs = BusinessSellerBlog::all();
        $key_contact_1 = '';
        $key_contact_2 = '';

        if (!$business_seller_data->isEmpty()){
            $business_seller = $business_seller_data->first();
            $banner = $business_seller->banner_image;
            if($business_seller->key_contact_1 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $business_seller->key_contact_1)->first();
                $key_contact_1 = ($key_contact_data != null)?$key_contact_data->name .'( '. $key_contact_data->position .' )' : null;
            }
            if($business_seller->key_contact_2 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $business_seller->key_contact_2)->first();
                $key_contact_2 = ($key_contact_data != null)?$key_contact_data->name .'( '. $key_contact_data->position .' )' : null;
            }
        }

        $data = [
                'business_seller_data' => $business_seller_data,
                'business_seller' => $business_seller,
                'banner' => $banner,
                'blogs' => $blogs,
                'key_contact_1' => $key_contact_1,
                'key_contact_2' => $key_contact_2
            ];

        return view('business_seller::index',$data);
    }

    public function editcontent()
    {
        $business_seller_data = BusinessSeller::all();
        $business_seller = [];
        $banner = '';
        $blog_count = 0;
        $blogs = BusinessSellerBlog::all();
        $latest_id = 0;
        $id_array = [];
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $key_contact_1 = 0;
        $key_contact_2 = 0;

        if (!$business_seller_data->isEmpty()){
            $business_seller = $business_seller_data->first();
            $banner = $business_seller->banner_image;
            $key_contact_1 = $business_seller->key_contact_1;
            $key_contact_2 = $business_seller->key_contact_2;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('business_seller_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }


        $data = [
                'business_seller_data' => $business_seller_data,
                'business_seller' => $business_seller,
                'hide_business_seller_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count,
                'ourteam' => $ourteam,
                'key_contact_1' => $key_contact_1,
                'key_contact_2' => $key_contact_2
            ];

        return view('business_seller::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($business_seller_banner_image = $request->file('business_seller_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($business_seller_banner_image))
                    {

                        $result = StorageHelper::store($path = 'public/business_seller', array($business_seller_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $business_seller_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $business_seller_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$business_seller_banner_image_path,
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

    public function updateContent(Request $request)
    {
        $business_seller_data = BusinessSeller::all();

        $action = '';
        if (!$business_seller_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = BusinessSeller::create($payload);
            }else{
                $this->businessSellerRepository->update(1, $payload, true);
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
                        $business_seller_blog = BusinessSellerBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->businessSellerBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->businessSellerBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Business Seller Successfully'));
            return redirect(route('business_seller.business_sellers.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_business_seller_image_ids;
        $payload['caption'] = $request->caption;

        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;

        if($request->key_contact_1 && $request->key_contact_1 != '')
        {
            $payload['key_contact_1'] = $request->key_contact_1;
        }
        if($request->key_contact_2 && $request->key_contact_2 != '')
        {
            $payload['key_contact_2'] = $request->key_contact_2;
        }
       
        return $payload;
    }

}
