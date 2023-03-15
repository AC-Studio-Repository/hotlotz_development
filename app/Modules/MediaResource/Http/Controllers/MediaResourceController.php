<?php

namespace App\Modules\MediaResource\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\MediaResource\Http\Requests\StoreMediaResourcePressReleaseRequest;
use App\Modules\MediaResource\Http\Requests\UpdateMediaResourcePressReleaseRequest;

use App\Modules\MediaResource\Models\MediaResource;
use App\Modules\MediaResource\Models\MediaResourceBlog;
use App\Modules\MediaResource\Models\MediaResourcePressRelease;

use App\Modules\MediaResource\Http\Repositories\MediaResourceRepository;
use App\Modules\MediaResource\Http\Repositories\MediaResourceBlogRepository;
use App\Modules\MediaResource\Http\Repositories\MediaResourcePressReleaseRepository;

class MediaResourceController extends BaseController
{
    protected $mediaResourceRepository;
    protected $mediaResourcePressReleaseRepository;
    protected $mediaResourceBlogRepository;
    public function __construct(MediaResourceRepository $mediaResourceRepository, MediaResourceBlogRepository $mediaResourceBlogRepository, MediaResourcePressReleaseRepository $mediaResourcePressReleaseRepository){
        $this->mediaResourceRepository = $mediaResourceRepository;
        $this->mediaResourcePressReleaseRepository = $mediaResourcePressReleaseRepository;
        $this->mediaResourceBlogRepository = $mediaResourceBlogRepository;
    }

    public function showlist()
    {
        return view('media_resource::showlist');
    }

    public function index()
    {
        $media_resources = $this->mediaResourcePressReleaseRepository->all([], false, 10);

        $data = [
            'media_resources' => $media_resources,
        ];

        return view('media_resource::index',$data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $document = '';
        $hid_record_id = 0;
        $lastest_id = 0;
        $media_resource = app(MediaResourcePressRelease::class);
        $lastest_data = DB::table('media_resource_press_releases')->latest('id')->first();
        if($lastest_data) {
            $lastest_id = $lastest_data->id;
        }

        $data = [
            'media_resource' => $media_resource,
            'hide_media_resource_doc_ids' => '',
            'document' => $document,
            'hid_record_id' => $hid_record_id,
            'lastest_id' => $lastest_id
        ];
        return view('media_resource::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMediaResourcePressReleaseRequest $request)
    {
        try {
           
            $media_resource = MediaResourcePressRelease::create($this->packData($request));
            flash()->success(__('Media Resource has been created'));
            return redirect(route('media_resource.media_resources.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the faq
     *
     * @param Item $faq
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(MediaResourcePressRelease $media_resource)
    {
        return view('media_resource::show', [
            'media_resource' => $media_resource
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MediaResourcePressRelease $media_resource)
    {
        $document = '';
        $hid_record_id = 0;
        $media_resource = $this->mediaResourcePressReleaseRepository->show('id', $media_resource->id, [], true);
        $lastest_id = DB::table('media_resource_press_releases')->latest('id')->first();
        
        $data = [
            'media_resource' => $media_resource,
            'hide_media_resource_doc_ids' => $media_resource->file_path,
            'banner' => $media_resource->file_path,
            'hid_record_id' => $media_resource->id,
            'lastest_id' => $lastest_id->id
        ];
        return view('media_resource::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMediaResourcePressReleaseRequest $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->mediaResourcePressReleaseRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => MediaResourcePressRelease::find($id)->title]));
            return redirect()->route('media_resource.media_resources.index')->with('success', 'Media Resource Updated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
                $this->mediaResourcePressReleaseRepository->destroy($id);
                DB::commit();

                return redirect()->route('media_resource.media_resources.index')->with('success', 'Media Resource Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('media_resource.media_resources.index')->with('fail', 'Media Resource Deactivating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['display_date'] = $request->display_date;
        $payload['title'] = $request->title;
        $payload['file_path'] = $request->hide_media_resource_doc_ids;
       
        return $payload;
    }

    public function documentUpload(Request $request)
    {
        try{
            if ($media_resource_document = $request->file('media_resource_document')) {

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($media_resource_document))
                    {
                        $inserted_id = 0;
                        
                        $edit_record_id = $request->hid_record_id;
                        $lastest_id = $request->lastest_id;

                        if($edit_record_id > 0) {
                            $inserted_id = $edit_record_id;
                        }else{
                            $inserted_id = $lastest_id + 1;
                        }

                        $result = StorageHelper::store($path = 'public/media_resource/document/'.$inserted_id, array($media_resource_document), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $media_resource_document_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $media_resource_document_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$media_resource_document_path,
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

    public function infoIndex()
    {
        $media_resource_data = MediaResource::all();
        $media_resource = [];
        $banner = '';
        $blogs = MediaResourceBlog::all();
        $hide_media_resource_asset_file_ids = '';

        if (!$media_resource_data->isEmpty()){
            $media_resource = $media_resource_data->first();
            $banner = $media_resource->banner_image;
            $hide_media_resource_asset_file_ids = $media_resource->our_asset_file_path;
        }

        $data = [
                'media_resource_data' => $media_resource_data,
                'media_resource' => $media_resource,
                'banner' => $banner,
                'blogs' => $blogs,
                'hide_media_resource_asset_file_ids' => $hide_media_resource_asset_file_ids
            ];

        return view('media_resource::info_index',$data);
    }

    public function editcontent()
    {
        $media_resource_data = MediaResource::all();
        $media_resource = [];
        $banner = '';
        $hide_media_resource_asset_file_ids = '';
        $blog_count = 0;
        $blogs = MediaResourceBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$media_resource_data->isEmpty()){
            $media_resource = $media_resource_data->first();
            $banner = $media_resource->banner_image;
            $hide_media_resource_asset_file_ids = $media_resource->our_asset_file_path;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('media_resource_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'media_resource_data' => $media_resource_data,
                'media_resource' => $media_resource,
                'hide_media_resource_image_ids' => $banner,
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count,
                'hide_media_resource_asset_file_ids' => $hide_media_resource_asset_file_ids
            ];

        return view('media_resource::edit_info_index',$data);
    }
    
    public function banner_image_upload(Request $request)
    {
        try{
            if ($media_resource_image = $request->file('media_resource_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $filepath = '';

                    if(isset($media_resource_image))
                    {
                        $result = StorageHelper::store($path = 'public/media_resource/info/banner', array($media_resource_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $filepath = $result[0]['data'];
                        $media_resource_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $media_resource_image_path,
                        ];
                    }
                }
                
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$media_resource_image_path,
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
        $media_resource_data = MediaResource::all();

        $action = '';
        if (!$media_resource_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packInfoData($request);

            if($action == 'save') {
                $result = MediaResource::create($payload);
            }else{
                $this->mediaResourceRepository->update(1, $payload, true);
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
                        $media_resource_blog = MediaResourceBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->mediaResourceBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->mediaResourceBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Media Resource Main Successfully'));
            return redirect(route('media_resource.media_resources.infoIndex')); 
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packInfoData($request)
    {
        $payload['banner_image'] = $request->hide_media_resource_image_ids;
        $payload['caption'] = $request->caption;

        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;

        $payload['contact_country_1'] = $request->contact_country_1;
        $payload['contact_email_1'] = $request->contact_email_1;

        $payload['contact_country_2'] = $request->contact_country_2;
        $payload['contact_email_2'] = $request->contact_email_2;

        $payload['our_asset_file_path'] = $request->hide_media_resource_asset_file_ids;
       
        return $payload;
    }

    public function asset_file_upload(Request $request)
    {
        try{
            if ($media_resource_asset_file = $request->file('media_resource_asset_file')) {

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($media_resource_asset_file))
                    {
                        $result = StorageHelper::store($path = 'public/media_resource/info/media_resource_asset_file', array($media_resource_asset_file), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $media_resource_asset_file_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $media_resource_asset_file_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$media_resource_asset_file_path,
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
