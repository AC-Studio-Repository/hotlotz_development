<?php

namespace App\Modules\AboutUs\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\AboutUs\Models\AboutUs;
use App\Modules\AboutUs\Models\AboutUsBlog;

use App\Modules\AboutUs\Http\Repositories\AboutUsRepository;
use App\Modules\AboutUs\Http\Repositories\AboutUsBlogRepository;

class AboutUsController extends BaseController
{
    protected $aboutusRepository;
    protected $aboutusBlogRepository;
    public function __construct(AboutUsRepository $aboutusRepository, AboutUsBlogRepository $aboutusBlogRepository){
        $this->aboutusRepository = $aboutusRepository;
        $this->aboutusBlogRepository = $aboutusBlogRepository;
    }

    public function index()
    {
        $about_us_data = AboutUs::all();
        $about_us = [];
        $banner = '';
        $blogs = AboutUsBlog::all();

        if (!$about_us_data->isEmpty()){
            $about_us = $about_us_data->first();
            $banner = $about_us->banner_image;
        }

        $data = [
                'about_us_data' => $about_us_data,
                'about_us' => $about_us,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('about_us::index',$data);
    }

    public function editcontent()
    {
        $about_us_data = AboutUs::all();
        $about_us = [];
        $banner = '';
        $blog_count = 0;
        $blogs = AboutUsBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$about_us_data->isEmpty()){
            $about_us = $about_us_data->first();
            $banner = $about_us->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('about_us_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'about_us_data' => $about_us_data,
                'about_us' => $about_us,
                'hide_aboutus_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count
            ];

        return view('about_us::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($aboutus_banner_image = $request->file('aboutus_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($aboutus_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/about_us', array($aboutus_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $aboutus_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $aboutus_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$aboutus_banner_image_path,
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
        $about_us_data = AboutUs::all();

        $action = '';
        if (!$about_us_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = AboutUs::create($payload);
            }else{
                $this->aboutusRepository->update(1, $payload, true);
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
                        $auction_cms_blog = AboutUsBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->aboutusBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->aboutusBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update AboutUs Successfully'));
            return redirect(route('about_us.about_uss.index')); 
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_aboutus_image_ids;
        $payload['caption'] = $request->caption;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;
       
        return $payload;
    }
}
