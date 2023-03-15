<?php

namespace App\Modules\StrategicPartner\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\StrategicPartner\Http\Requests\StoreStrategicPartnerRequest;
use App\Modules\StrategicPartner\Http\Requests\UpdateStrategicPartnerRequest;

use App\Modules\StrategicPartner\Models\StrategicPartner;
use App\Modules\StrategicPartner\Models\StrategicPartnerInfo;
use App\Modules\StrategicPartner\Models\StrategicPartnerInfoBlog;

use App\Modules\StrategicPartner\Http\Repositories\StrategicPartnerRepository;
use App\Modules\StrategicPartner\Http\Repositories\StrategicPartnerInfoRepository;
use App\Modules\StrategicPartner\Http\Repositories\StrategicPartnerInfoBlogRepository;

class StrategicPartnerController extends BaseController
{
    protected $strategicPartnerRepository;
    protected $strategicPartnerInfoRepository;
    protected $strategicPartnerInfoBlogRepository;
    public function __construct(StrategicPartnerRepository $strategicPartnerRepository, StrategicPartnerInfoRepository $strategicPartnerInfoRepository, StrategicPartnerInfoBlogRepository $strategicPartnerInfoBlogRepository){
        $this->strategicPartnerRepository = $strategicPartnerRepository;
        $this->strategicPartnerInfoRepository = $strategicPartnerInfoRepository;
        $this->strategicPartnerInfoBlogRepository = $strategicPartnerInfoBlogRepository;
    }

    public function showlist()
    {
        return view('strategic_partner::showlist');
    }

    public function index()
    {
        $strategic_partners = $this->strategicPartnerRepository->all([], false, 10);

        $data = [
            'strategic_partners' => $strategic_partners,
        ];

        return view('strategic_partner::index',$data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner = '';
        $hid_record_id = 0;
        $lastest_id = 0;
        $strategic_partner = app(StrategicPartner::class);
        $lastest_data = DB::table('strategic_partners')->latest('id')->first();
        if($lastest_data)
        {
            $lastest_id = $lastest_data->id;
        }

        $data = [
            'strategic_partner' => $strategic_partner,
            'hide_image_ids' => '',
            'banner' => $banner,
            'hid_record_id' => $hid_record_id,
            'lastest_id' => $lastest_id
        ];
        return view('strategic_partner::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStrategicPartnerRequest $request)
    {
        try {
           
            $faq = StrategicPartner::create($this->packData($request));
            flash()->success(__('Strategic Partner has been created'));
            return redirect(route('strategic_partner.strategic_partners.index'));
            
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
    public function show(StrategicPartner $strategicPartner)
    {
        return view('strategic_partner::show', [
            'strategic_partner' => $strategicPartner
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StrategicPartner $strategicPartner)
    {
        $banner = '';
        $hid_record_id = 0;
        $strategic_partner = $this->strategicPartnerRepository->show('id', $strategicPartner->id, [], true);
        $lastest_id = DB::table('strategic_partners')->latest('id')->first();
        
        $data = [
            'strategic_partner' => $strategic_partner,
            'hide_image_ids' => $strategic_partner->full_file_path,
            'banner' => $strategic_partner->full_file_path,
            'hid_record_id' => $strategic_partner->id,
            'lastest_id' => $lastest_id->id
        ];
        return view('strategic_partner::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStrategicPartnerRequest $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->strategicPartnerRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => StrategicPartner::find($id)->title]));
            return redirect()->route('strategic_partner.strategic_partners.index')->with('success', 'Strtegic Partner Updated Successfully!');
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
                $this->strategicPartnerRepository->destroy($id);
                DB::commit();

                return redirect()->route('strategic_partner.strategic_partners.index')->with('success', 'Strtegic Partner Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('strategic_partner.strategic_partners.index')->with('fail', 'Strtegic Partner Deactivating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['title'] = $request->title;
        $payload['description'] = $request->description;
        $payload['image'] = $request->hide_sp_image_ids;
        $payload['full_file_path'] = $request->hide_sp_full_filepath;
       
        return $payload;
    }

    public function itemImageUpload(Request $request)
    {
        try{
            if ($strategic_partner_image = $request->file('sp_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($strategic_partner_image))
                    {
                        $inserted_id = 0;
                        
                        $edit_record_id = $request->hid_record_id;
                        $lastest_id = $request->lastest_id;

                        if($edit_record_id > 0) {
                            $inserted_id = $edit_record_id;
                        }else{
                            $inserted_id = $lastest_id + 1;
                        }

                        $result = StorageHelper::store($path = 'public/strategic_partners/'.$inserted_id, array($strategic_partner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $strategic_partner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $strategic_partner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$filename,
                    'saved_storage_filepath'=>$strategic_partner_image_path,
                    'ids'=>$images_ids,
                    'initialPreview' => $p1, 
                    'initialPreviewConfig' => $p2,   
                    'append' => true // whether to append these configurations to initialPreview.
                                     // if set to false it will overwrite initial preview
                                     // if set to true it will append to initial preview
                                     // if this propery not set or passed, it will default to true.
                ];

                return json_encode($data);
        } catch (Exception $e) {
            return json_encode(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function infoIndex()
    {
        $strategic_partner_data = StrategicPartnerInfo::all();
        $strategic_partner = [];
        $banner = '';
        $blogs = StrategicPartnerInfoBlog::all();

        if (!$strategic_partner_data->isEmpty()){
            $strategic_partner = $strategic_partner_data->first();
            $banner = $strategic_partner->banner_image;
        }

        $data = [
                'strategic_partner_data' => $strategic_partner_data,
                'strategic_partner' => $strategic_partner,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('strategic_partner::info_index',$data);
    }

    public function editcontent()
    {
        $strategic_partner_data = StrategicPartnerInfo::all();
        $strategic_partner = [];
        $banner = '';
        $blog_count = 0;
        $blogs = StrategicPartnerInfoBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$strategic_partner_data->isEmpty()){
            $strategic_partner = $strategic_partner_data->first();
            $banner = $strategic_partner->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('strategic_partners_info_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'strategic_partner_data' => $strategic_partner_data,
                'strategic_partner' => $strategic_partner,
                'hide_sp_info_image_ids' => $banner,
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count
            ];

        return view('strategic_partner::edit_info_index',$data);
    }
    
    public function banner_image_upload(Request $request)
    {
        try{
            if ($strategic_partner_image = $request->file('sp_info_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $filepath = '';

                    if(isset($strategic_partner_image))
                    {
                        $result = StorageHelper::store($path = 'public/strategic_partners_info', array($strategic_partner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $filepath = $result[0]['data'];
                        $strategic_partner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $strategic_partner_image_path,
                        ];
                    }
                }
                
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$strategic_partner_image_path,
                    'ids'=>$images_ids,
                    'initialPreview' => $p1, 
                    'initialPreviewConfig' => $p2,   
                    'append' => true // whether to append these configurations to initialPreview.
                                     // if set to false it will overwrite initial preview
                                     // if set to true it will append to initial preview
                                     // if this propery not set or passed, it will default to true.
                ];

                return json_encode($data);
        } catch (Exception $e) {
            return json_encode(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function updateContent(Request $request)
    {
        $strategic_partner_data = StrategicPartnerInfo::all();

        $action = '';
        if (!$strategic_partner_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packInfoData($request);

            if($action == 'save') {
                $result = StrategicPartnerInfo::create($payload);
            }else{
                $this->strategicPartnerInfoRepository->update(1, $payload, true);
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
                        $strategic_partner_info_blog = StrategicPartnerInfoBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->strategicPartnerInfoBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->strategicPartnerInfoBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Strategic Partner Main Successfully'));
            return redirect(route('strategic_partner.strategic_partners.infoIndex')); 
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packInfoData($request)
    {
        $payload['banner_image'] = $request->hide_sp_info_image_ids;
        $payload['caption'] = $request->caption;

        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;
       
        return $payload;
    }
}
