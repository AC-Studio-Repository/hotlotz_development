<?php

namespace App\Modules\MarketplaceCms\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\MarketplaceCms\Models\MarketplaceCms;
use App\Modules\MarketplaceCms\Models\MarketplaceCmsBlog;

use App\Modules\MarketplaceCms\Http\Repositories\MarketplaceCmsRepository;
use App\Modules\MarketplaceCms\Http\Repositories\MarketplaceCmsBlogRepository;

class MarketplaceCmsController extends BaseController
{
    protected $marketplacecmsRepository;
    protected $marketplacecmsBlogRepository;
    public function __construct(MarketplaceCmsRepository $marketplacecmsRepository, MarketplaceCmsBlogRepository $marketplacecmsBlogRepository){
        $this->marketplacecmsRepository = $marketplacecmsRepository;
        $this->marketplacecmsBlogRepository = $marketplacecmsBlogRepository;
    }

    public function index()
    {
        $marketplace_cms_data = MarketplaceCms::all();
        $marketplace_cms = [];
        $banner = '';
        $blogs = MarketplaceCmsBlog::all();

        if (!$marketplace_cms_data->isEmpty()){
            $marketplace_cms = $marketplace_cms_data->first();
            $banner = $marketplace_cms->banner_image;
        }

        $data = [
                'marketplace_cms_data' => $marketplace_cms_data,
                'marketplace_cms' => $marketplace_cms,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('marketplace_cms::index',$data);
    }

    public function editcontent()
    {
        $marketplace_cms_data = MarketplaceCms::all();
        $marketplace_cms = [];
        $banner = '';
        $blog_count = 0;
        $blogs = MarketplaceCmsBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$marketplace_cms_data->isEmpty()){
            $marketplace_cms = $marketplace_cms_data->first();
            $banner = $marketplace_cms->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('marketplace_cms_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'marketplace_cms_data' => $marketplace_cms_data,
                'marketplace_cms' => $marketplace_cms,
                'hide_marketplacecms_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count
            ];

        return view('marketplace_cms::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($marketplacecms_banner_image = $request->file('marketplacecms_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($marketplacecms_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/marketplace_cms', array($marketplacecms_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $marketplacecms_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $marketplacecms_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$marketplacecms_banner_image_path,
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
        $marketplace_cms_data = MarketplaceCms::all();

        $action = '';
        if (!$marketplace_cms_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = MarketplaceCms::create($payload);
            }else{
                $this->marketplacecmsRepository->update(1, $payload, true);
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
                        $marketplace_cms_blog = MarketplaceCmsBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->marketplacecmsBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->marketplacecmsBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Marketplace Successfully'));
            return redirect(route('marketplace_cms.marketplace_cmss.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_marketplacecms_image_ids;
        $payload['caption'] = $request->caption;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;
       
        return $payload;
    }

}
