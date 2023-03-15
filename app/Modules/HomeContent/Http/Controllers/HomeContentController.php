<?php

namespace App\Modules\HomeContent\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\HomeContent\Models\HomeContent;
use App\Modules\HomeContent\Models\HomeContentBlog;
use App\Modules\OurTeam\Models\OurTeam;

use App\Modules\HomeContent\Http\Repositories\HomeContentRepository;
use App\Modules\HomeContent\Http\Repositories\HomeContentBlogRepository;

class HomeContentController extends BaseController
{
    protected $homeContentRepository;
    protected $homeContentBlogRepository;
    public function __construct(HomeContentRepository $homeContentRepository, HomeContentBlogRepository $homeContentBlogRepository){
        $this->homeContentRepository = $homeContentRepository;
        $this->homeContentBlogRepository = $homeContentBlogRepository;
    }

    public function index()
    {
        $home_content_data = HomeContent::all();
        $home_content = [];
        $banner = '';
        $blogs = HomeContentBlog::all();
        $key_contact_1 = '';
        $key_contact_2 = '';

        if (!$home_content_data->isEmpty()){
            $home_content = $home_content_data->first();
            $banner = $home_content->banner_image;
            if($home_content->key_contact_1 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $home_content->key_contact_1)->first();
                $key_contact_1 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
            }
            if($home_content->key_contact_2 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $home_content->key_contact_2)->first();
                $key_contact_2 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
            }
        }

        $data = [
                'home_content_data' => $home_content_data,
                'home_content' => $home_content,
                'banner' => $banner,
                'blogs' => $blogs,
                'key_contact_1' => $key_contact_1,
                'key_contact_2' => $key_contact_2
            ];

        return view('home_content::index',$data);
    }

    public function editcontent()
    {
        $home_content_data = HomeContent::all();
        $home_content = [];
        $banner = '';
        $blog_count = 0;
        $blogs = HomeContentBlog::all();
        $latest_id = 0;
        $id_array = [];
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $key_contact_1 = 0;
        $key_contact_2 = 0;

        if (!$home_content_data->isEmpty()){
            $home_content = $home_content_data->first();
            $banner = $home_content->banner_image;
            $key_contact_1 = $home_content->key_contact_1;
            $key_contact_2 = $home_content->key_contact_2;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('home_content_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'home_content_data' => $home_content_data,
                'home_content' => $home_content,
                'hide_homecontent_image_ids' => '',
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

        return view('home_content::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($homecontent_banner_image = $request->file('homecontent_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($homecontent_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/home_content', array($homecontent_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $homecontent_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $homecontent_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$homecontent_banner_image_path,
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
        $home_content_data = HomeContent::all();

        $action = '';
        if (!$home_content_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = HomeContent::create($payload);
            }else{
                $this->homeContentRepository->update(1, $payload, true);
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
                        $home_content_blog = HomeContentBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->homeContentBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->homeContentBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Home Contents Successfully'));
            return redirect(route('home_content.home_contents.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_homecontent_image_ids;
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
