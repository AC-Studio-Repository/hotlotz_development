<?php

namespace App\Modules\PrivateCollections\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\PrivateCollections\Models\PrivateCollections;
use App\Modules\PrivateCollections\Models\PrivateCollectionsBlog;
use App\Modules\OurTeam\Models\OurTeam;

use App\Modules\PrivateCollections\Http\Repositories\PrivateCollectionsRepository;
use App\Modules\PrivateCollections\Http\Repositories\PrivateCollectionsBlogRepository;

class PrivateCollectionsController extends BaseController
{
    protected $privateCollectionsRepository;
    protected $privateCollectionsBlogRepository;
    public function __construct(PrivateCollectionsRepository $privateCollectionsRepository, PrivateCollectionsBlogRepository $privateCollectionsBlogRepository){
        $this->privateCollectionsRepository = $privateCollectionsRepository;
        $this->privateCollectionsBlogRepository = $privateCollectionsBlogRepository;
    }

    public function index()
    {
        $private_collections_data = PrivateCollections::all();
        $private_collections = [];
        $banner = '';
        $blogs = PrivateCollectionsBlog::all();
        $key_contact_1 = '';
        $key_contact_2 = '';

        if (!$private_collections_data->isEmpty()){
            $private_collections = $private_collections_data->first();
            $banner = $private_collections->banner_image;
            if($private_collections->key_contact_1 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $private_collections->key_contact_1)->first();
                $key_contact_1 = ($key_contact_data != null)?$key_contact_data->name .'( '. $key_contact_data->position .' )' : null;
            }
            if($private_collections->key_contact_2 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $private_collections->key_contact_2)->first();
                $key_contact_2 = ($key_contact_data != null)?$key_contact_data->name .'( '. $key_contact_data->position .' )' : null;
            }
        }

        $data = [
            'private_collections_data' => $private_collections_data,
            'private_collections' => $private_collections,
            'banner' => $banner,
            'blogs' => $blogs,
            'key_contact_1' => $key_contact_1,
            'key_contact_2' => $key_contact_2
        ];

        return view('private_collections::index',$data);
    }

    public function editcontent()
    {
        $private_collections_data = PrivateCollections::all();
        $private_collections = [];
        $banner = '';
        $blog_count = 0;
        $blogs = PrivateCollectionsBlog::all();
        $latest_id = 0;
        $id_array = [];
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $key_contact_1 = 0;
        $key_contact_2 = 0;

        if (!$private_collections_data->isEmpty()){
            $private_collections = $private_collections_data->first();
            $banner = $private_collections->banner_image;
            $key_contact_1 = $private_collections->key_contact_1;
            $key_contact_2 = $private_collections->key_contact_2;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('private_collections_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'private_collections_data' => $private_collections_data,
                'private_collections' => $private_collections,
                'hide_private_collections_image_ids' => '',
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

        return view('private_collections::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($private_collections_banner_image = $request->file('private_collections_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($private_collections_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/private_collections', array($private_collections_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $private_collections_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $private_collections_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$private_collections_banner_image_path,
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
        $private_collections_data = PrivateCollections::all();

        $action = '';
        if (!$private_collections_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = PrivateCollections::create($payload);
            }else{
                $this->privateCollectionsRepository->update(1, $payload, true);
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
                        $private_collections_blog = PrivateCollectionsBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->privateCollectionsBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->privateCollectionsBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Private Collections Successfully'));
            return redirect(route('private_collections.private_collectionss.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_private_collections_image_ids;
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
