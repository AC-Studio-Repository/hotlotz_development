<?php

namespace App\Modules\AuctionCms\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\AuctionCms\Models\AuctionCms;
use App\Modules\AuctionCms\Models\AuctionCmsBlog;

use App\Modules\AuctionCms\Http\Repositories\AuctionCmsRepository;
use App\Modules\AuctionCms\Http\Repositories\AuctionCmsBlogRepository;


class AuctionCmsController extends BaseController
{
    protected $auctioncmsRepository;
    protected $auctioncmsBlogRepository;
    public function __construct(AuctionCmsRepository $auctioncmsRepository, AuctionCmsBlogRepository $auctioncmsBlogRepository){
        $this->auctioncmsRepository = $auctioncmsRepository;
        $this->auctioncmsBlogRepository = $auctioncmsBlogRepository;
    }

    public function index()
    {
        $auction_cms_data = AuctionCms::all();
        $auction_cms = [];
        $banner = '';
        $blogs = AuctionCmsBlog::all();

        if (!$auction_cms_data->isEmpty()){
            $auction_cms = $auction_cms_data->first();
            $banner = $auction_cms->banner_image;
        }

        $data = [
                'auction_cms_data' => $auction_cms_data,
                'auction_cms' => $auction_cms,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('auction_cms::index',$data);
    }

    public function editcontent()
    {
        $blog_count = 0;
        $auction_cms_data = AuctionCms::all();
        $auction_cms = [];
        $banner = '';
        $blogs = AuctionCmsBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$auction_cms_data->isEmpty()){
            $auction_cms = $auction_cms_data->first();
            $banner = $auction_cms->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('auction_cms_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'auction_cms_data' => $auction_cms_data,
                'auction_cms' => $auction_cms,
                'hide_auctioncms_image_ids' => '',
                'banner' => $banner,
                'blogs' => $blogs,
                'blog_count' => $blog_count,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array
            ];

        return view('auction_cms::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($auctioncms_banner_image = $request->file('auctioncms_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($auctioncms_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/auction_cms', array($auctioncms_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $auctioncms_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $auctioncms_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$auctioncms_banner_image_path,
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
        // dd($request->all());
        $auction_cms_data = AuctionCms::all();

        $action = '';
        if (!$auction_cms_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = AuctionCms::create($payload);
            }else{
                $this->auctioncmsRepository->update(1, $payload, true);
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
                        $auction_cms_blog = AuctionCmsBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        // $blog_data = AuctionCmsBlog::all();
                        // if($blog_data->count() > 1)
                        // {
                            $this->auctioncmsBlogRepository->destroy($hid_delete_id);
                            DB::commit();
                        // }else{
                        //     AuctionCmsBlog::truncate();
                        // }
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->auctioncmsBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }

           // return response()->json(array('status' => '1','message'=>'Update How To Sell Successfully.'));
            flash()->success(__('Update Auction Successfully'));
            return redirect(route('auction_cms.auction_cmss.index'));
        } catch (\Exception $e) {
            DB::rollback();
            // return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_auctioncms_image_ids;
        $payload['caption'] = $request->caption;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;
        // $payload['blog_header_2'] = $request->blog_header_2;
        // $payload['blog_2'] = $request->blog_2;
        // $payload['blog_header_3'] = $request->blog_header_3;
        // $payload['blog_3'] = $request->blog_3;
       
        return $payload;
    }

}
