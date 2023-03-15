<?php

namespace App\Modules\HowToBuy\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\HowToBuy\Models\HowToBuy;
use App\Modules\HowToBuy\Models\HowToBuyBlog;

use App\Modules\HowToBuy\Http\Repositories\HowToBuyRepository;
use App\Modules\HowToBuy\Http\Repositories\HowToBuyBlogRepository;

class HowToBuyController extends BaseController
{
    protected $howtobuyRepository;
    protected $howtobuyBlogRepository;
    public function __construct(HowToBuyRepository $howtobuyRepository, HowToBuyBlogRepository $howtobuyBlogRepository){
        $this->howtobuyRepository = $howtobuyRepository;
        $this->howtobuyBlogRepository = $howtobuyBlogRepository;
    }

    public function index()
    {
        $how_to_buy_data = HowToBuy::all();
        $how_to_buy = [];
        $banner = '';
        $blogs = HowToBuyBlog::all();

        if (!$how_to_buy_data->isEmpty()){
            $how_to_buy = $how_to_buy_data->first();
            $banner = $how_to_buy->banner_image;
        }

        $data = [
                'how_to_buy_data' => $how_to_buy_data,
                'how_to_buy' => $how_to_buy,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('how_to_buy::index',$data);
    }

    public function editcontent()
    {
        $how_to_buy_data = HowToBuy::all();
        $how_to_buy = [];
        $banner = '';
        $blog_count = 0;
        $blogs = HowToBuyBlog::all();
        $latest_id = 0;
        $id_array = [];
        $hide_how_to_buy_uploaded_file_ids = '';
        $hide_how_to_buy_uploaded_filename = '';

        if (!$how_to_buy_data->isEmpty()){
            $how_to_buy = $how_to_buy_data->first();
            $banner = $how_to_buy->banner_image;
            $hide_how_to_buy_uploaded_file_ids = $how_to_buy->uploaded_filen_path;
            $hide_how_to_buy_uploaded_filename = $how_to_buy->uploaded_filename;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('how_to_buy_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'how_to_buy_data' => $how_to_buy_data,
                'how_to_buy' => $how_to_buy,
                'hide_howtobuy_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count,
                'hide_how_to_buy_uploaded_file_ids' => $hide_how_to_buy_uploaded_file_ids,
                'hide_how_to_buy_uploaded_filename' => $hide_how_to_buy_uploaded_filename
            ];

        return view('how_to_buy::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($howtobuy_banner_image = $request->file('howtobuy_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($howtobuy_banner_image))
                    {

                        $result = StorageHelper::store($path = 'public/how_to_buy/banner', array($howtobuy_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $howtobuy_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $howtobuy_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$howtobuy_banner_image_path,
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
        $how_to_buy_data = HowToBuy::all();

        $action = '';
        if (!$how_to_buy_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = HowToBuy::create($payload);
            }else{
                $this->howtobuyRepository->update(1, $payload, true);
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
                        $how_to_buy_blog = HowToBuyBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->howtobuyBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->howtobuyBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
            flash()->success(__('Update How To Buy Successfully'));
            return redirect(route('how_to_buy.how_to_buys.index'));   
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_howtobuy_image_ids;
        $payload['caption'] = $request->caption;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;
        $payload['uploaded_filen_path'] = $request->hide_how_to_buy_uploaded_file_ids;
        $payload['uploaded_filename'] = $request->hide_how_to_buy_uploaded_filename;
       
        return $payload;
    }

    public function file_upload(Request $request)
    {
        try{
            if ($how_to_buy_uploaded_file = $request->file('how_to_buy_file_upload')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
            
                    if(isset($how_to_buy_uploaded_file))
                    {
                        $orignal_name = $how_to_buy_uploaded_file->getClientOriginalName();
                        $image_properties = $orignal_name;

                        $result = StorageHelper::store($path = 'public/how_to_buy/uploaded_file', array($how_to_buy_uploaded_file), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $how_to_buy_uploaded_file_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $how_to_buy_uploaded_file_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$how_to_buy_uploaded_file_path,
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
}
