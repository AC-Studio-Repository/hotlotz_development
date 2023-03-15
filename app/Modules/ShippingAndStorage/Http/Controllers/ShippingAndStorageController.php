<?php

namespace App\Modules\ShippingAndStorage\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\ShippingAndStorage\Models\ShippingAndStorage;
use App\Modules\ShippingAndStorage\Models\ShippingAndStorageBlog;

use App\Modules\ShippingAndStorage\Http\Repositories\ShippingAndStorageRepository;
use App\Modules\ShippingAndStorage\Http\Repositories\ShippingAndStorageBlogRepository;

class ShippingAndStorageController extends BaseController
{
    protected $shippingAndStorageRepository;
    protected $shippingAndStorageBlogRepository;
    public function __construct(ShippingAndStorageRepository $shippingAndStorageRepository, ShippingAndStorageBlogRepository $shippingAndStorageBlogRepository){
        $this->shippingAndStorageRepository = $shippingAndStorageRepository;
        $this->shippingAndStorageBlogRepository = $shippingAndStorageBlogRepository;
    }

    public function index()
    {
        $shipping_and_storage_data = ShippingAndStorage::all();
        $shipping_and_storage = [];
        $banner = '';
        $blogs = ShippingAndStorageBlog::all();

        if (!$shipping_and_storage_data->isEmpty()){
            $shipping_and_storage = $shipping_and_storage_data->first();
            $banner = $shipping_and_storage->banner_image;
        }

        $data = [
                'shipping_and_storage_data' => $shipping_and_storage_data,
                'shipping_and_storage' => $shipping_and_storage,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('shipping_and_storage::index',$data);
    }

    public function editcontent()
    {
        $shipping_and_storage_data = ShippingAndStorage::all();
        $shipping_and_storage = [];
        $banner = '';
        $blog_count = 0;
        $blogs = ShippingAndStorageBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$shipping_and_storage_data->isEmpty()){
            $shipping_and_storage = $shipping_and_storage_data->first();
            $banner = $shipping_and_storage->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('shipping_and_storage_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'shipping_and_storage_data' => $shipping_and_storage_data,
                'shipping_and_storage' => $shipping_and_storage,
                'hide_shipping_and_storage_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count
            ];

        return view('shipping_and_storage::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($shipping_and_storage_banner_image = $request->file('shipping_and_storage_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($shipping_and_storage_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/shipping_and_storage', array($shipping_and_storage_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $shipping_and_storage_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $shipping_and_storage_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$shipping_and_storage_banner_image_path,
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
        $shipping_and_storage_data = ShippingAndStorage::all();

        $action = '';
        if (!$shipping_and_storage_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = ShippingAndStorage::create($payload);
            }else{
                $this->shippingAndStorageRepository->update(1, $payload, true);
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
                        $shipping_and_storage_blog = ShippingAndStorageBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->shippingAndStorageBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->shippingAndStorageBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Shipping And Storage Successfully'));
            return redirect(route('shipping_and_storage.shipping_and_storages.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_shipping_and_storage_image_ids;
        $payload['caption'] = $request->caption;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;

        return $payload;
    }

}
