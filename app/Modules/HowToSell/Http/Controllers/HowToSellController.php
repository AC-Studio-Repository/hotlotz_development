<?php

namespace App\Modules\HowToSell\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\HowToSell\Models\HowToSell;
use App\Modules\HowToSell\Models\HowToSellBlog;

use App\Modules\HowToSell\Http\Repositories\HowToSellRepository;
use App\Modules\HowToSell\Http\Repositories\HowToSellBlogRepository;

class HowToSellController extends BaseController
{
    protected $howtosellRepository;
    protected $howtosellBlogRepository;
    public function __construct(HowToSellRepository $howtosellRepository, HowToSellBlogRepository $howtosellBlogRepository){
        $this->howtosellRepository = $howtosellRepository;
        $this->howtosellBlogRepository = $howtosellBlogRepository;
    }

    public function index()
    {
        $how_to_sell_data = HowToSell::all();
        $how_to_sell = [];
        $banner = '';
        $blogs = HowToSellBlog::all();

        if (!$how_to_sell_data->isEmpty()){
            $how_to_sell = $how_to_sell_data->first();
            $banner = $how_to_sell->banner_image;
        }

        $data = [
                'how_to_sell_data' => $how_to_sell_data,
                'how_to_sell' => $how_to_sell,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('how_to_sell::index',$data);
    }

    public function editcontent()
    {
        $how_to_sell_data = HowToSell::all();
        $how_to_sell = [];
        $banner = '';
        $blog_count = 0;
        $blogs = HowToSellBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$how_to_sell_data->isEmpty()){
            $how_to_sell = $how_to_sell_data->first();
            $banner = $how_to_sell->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('how_to_sell_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'how_to_sell_data' => $how_to_sell_data,
                'how_to_sell' => $how_to_sell,
                'hide_howtosell_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count
            ];

        return view('how_to_sell::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($howtosell_banner_image = $request->file('howtosell_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($howtosell_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/how_to_sell', array($howtosell_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $howtosell_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $howtosell_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$howtosell_banner_image_path,
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
        $how_to_sell_data = HowToSell::all();

        $action = '';
        if (!$how_to_sell_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = HowToSell::create($payload);
            }else{
                $this->howtosellRepository->update(1, $payload, true);
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
                        $how_to_sell_blog = HowToSellBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->howtosellBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->howtosellBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update How To Sell Successfully'));
            return redirect(route('how_to_sell.how_to_sells.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_howtosell_image_ids;
        $payload['caption'] = $request->caption;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;
        
        return $payload;
    }

}
