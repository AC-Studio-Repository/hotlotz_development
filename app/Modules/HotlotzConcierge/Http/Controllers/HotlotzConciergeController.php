<?php

namespace App\Modules\HotlotzConcierge\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\HotlotzConcierge\Models\HotlotzConcierge;
use App\Modules\HotlotzConcierge\Models\HotlotzConciergeBlog;
use App\Modules\OurTeam\Models\OurTeam;

use App\Modules\HotlotzConcierge\Http\Repositories\HotlotzConciergeRepository;
use App\Modules\HotlotzConcierge\Http\Repositories\HotlotzConciergeBlogRepository;

class HotlotzConciergeController extends BaseController
{
    protected $hotlotzConciergeRepository;
    protected $hotlotzConciergeBlogRepository;
    public function __construct(HotlotzConciergeRepository $hotlotzConciergeRepository, HotlotzConciergeBlogRepository $hotlotzConciergeBlogRepository){
        $this->hotlotzConciergeRepository = $hotlotzConciergeRepository;
        $this->hotlotzConciergeBlogRepository = $hotlotzConciergeBlogRepository;
    }

    public function index()
    {
        $hotlotz_concierge_data = HotlotzConcierge::all();
        $hotlotz_concierge = [];
        $banner = '';
        $blogs = HotlotzConciergeBlog::all();
        $key_contact_1 = '';
        $key_contact_2 = '';

        if (!$hotlotz_concierge_data->isEmpty()){
            $hotlotz_concierge = $hotlotz_concierge_data->first();
            $banner = $hotlotz_concierge->banner_image;
            if($hotlotz_concierge->key_contact_1 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $hotlotz_concierge->key_contact_1)->first();
                $key_contact_1 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
            }
            if($hotlotz_concierge->key_contact_2 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $hotlotz_concierge->key_contact_2)->first();
                $key_contact_2 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
            }
        }

        $data = [
                'hotlotz_concierge_data' => $hotlotz_concierge_data,
                'hotlotz_concierge' => $hotlotz_concierge,
                'banner' => $banner,
                'blogs' => $blogs,
                'key_contact_1' => $key_contact_1,
                'key_contact_2' => $key_contact_2
            ];

        return view('hotlotz_concierge::index',$data);
    }

    public function editcontent()
    {
        $hotlotz_concierge_data = HotlotzConcierge::all();
        $hotlotz_concierge = [];
        $banner = '';
        $blog_count = 0;
        $blogs = HotlotzConciergeBlog::all();
        $latest_id = 0;
        $id_array = [];
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $key_contact_1 = 0;
        $key_contact_2 = 0;

        if (!$hotlotz_concierge_data->isEmpty()){
            $hotlotz_concierge = $hotlotz_concierge_data->first();
            $banner = $hotlotz_concierge->banner_image;
            $key_contact_1 = $hotlotz_concierge->key_contact_1;
            $key_contact_2 = $hotlotz_concierge->key_contact_2;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();

            $latest_data = DB::table('hotlotz_concierge_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'hotlotz_concierge_data' => $hotlotz_concierge_data,
                'hotlotz_concierge' => $hotlotz_concierge,
                'hide_hotlotzConcierge_image_ids' => '',
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

        return view('hotlotz_concierge::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($hotlotz_concierge_banner_image = $request->file('hotlotz_concierge_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($hotlotz_concierge_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/hotlotz_concierge', array($hotlotz_concierge_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $hotlotz_concierge_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $hotlotz_concierge_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$hotlotz_concierge_banner_image_path,
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
        $hotlotz_concierge_data = HotlotzConcierge::all();

        $action = '';
        if (!$hotlotz_concierge_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = HotlotzConcierge::create($payload);
            }else{
                $this->hotlotzConciergeRepository->update(1, $payload, true);
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
                        $hotlotz_concierge_blog = HotlotzConciergeBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->hotlotzConciergeBlogRepository->destroy($hid_delete_id);
                        DB::commit();

                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->hotlotzConciergeBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }

            flash()->success(__('Update Estate Services Successfully'));
            return redirect(route('hotlotz_concierge.hotlotz_concierges.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_hotlotzConcierge_image_ids;
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
