<?php

namespace App\Modules\Careers\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\Careers\Http\Requests\StoreCareersJobRequest;
use App\Modules\Careers\Http\Requests\UpdateCareersJobRequest;

use App\Modules\Careers\Models\CareersInfo;
use App\Modules\Careers\Models\CareersBlog;
use App\Modules\Careers\Models\Careers;

use App\Modules\Careers\Http\Repositories\CareersInfoRepository;
use App\Modules\Careers\Http\Repositories\CareersBlogRepository;
use App\Modules\Careers\Http\Repositories\CareersRepository;

class CareersController extends BaseController
{
    protected $careersInfoRepository;
    protected $careersRepository;
    protected $careersBlogRepository;
    public function __construct(CareersInfoRepository $careersInfoRepository, CareersRepository $careersRepository, CareersBlogRepository $careersBlogRepository){
        $this->careersInfoRepository = $careersInfoRepository;
        $this->careersRepository = $careersRepository;
        $this->careersBlogRepository = $careersBlogRepository;
    }

    public function showlist()
    {
        return view('careers::showlist');
    }

    public function index()
    {
//         $routeCollection = \Route::getRoutes();
//         $r = $routeCollection->getRoutes();
// foreach ($r as $value) {

//     echo($value->uri());
//     echo "<br><br>";
// }
        $careers = $this->careersRepository->all([], false, 10);

        $data = [
            'careers' => $careers,
        ];

        return view('careers::index',$data);
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
        $careers = app(Careers::class);
        $lastest_data = DB::table('careers_jobs')->latest('id')->first();
        if($lastest_data) {
            $lastest_id = $lastest_data->id;
        }

        $data = [
            'career' => $careers,
            'hide_careers_doc_ids' => '',
            'document' => $document,
            'hid_record_id' => $hid_record_id,
            'lastest_id' => $lastest_id
        ];
        return view('careers::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCareersJobRequest $request)
    {
        try {
           
            $career = Careers::create($this->packData($request));
            flash()->success(__('Careers has been created'));
            return redirect(route('careers.careerss.index'));
            
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
    public function show(Careers $careerss)
    {
        return view('careers::show', [
            'career' => $careerss
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Careers $careerss)
    {
        $document = '';
        $hid_record_id = 0;
        $careers = $this->careersRepository->show('id', $careerss->id, [], true);
        $lastest_id = DB::table('careers_jobs')->latest('id')->first();
        
        $data = [
            'career' => $careers,
            'hide_careers_doc_ids' => $careers->file_path,
            'banner' => $careers->file_path,
            'hid_record_id' => $careers->id,
            'lastest_id' => $lastest_id->id
        ];
        return view('careers::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCareersJobRequest $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->careersRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => Careers::find($id)->position]));
            return redirect()->route('careers.careerss.index')->with('success', 'Careers Updated Successfully!');
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
                $this->careersRepository->destroy($id);
                DB::commit();

                return redirect()->route('careers.careerss.index')->with('success', 'Careers Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('careers.careerss.index')->with('fail', 'Careers Deactivating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['position'] = $request->position;
        $payload['expreience_level'] = $request->expreience_level;
        $payload['posts'] = $request->posts;
        $payload['file_path'] = $request->hide_careers_doc_ids;
       
        return $payload;
    }

    public function documentUpload(Request $request)
    {
        try{
            if ($careers_document = $request->file('careers_document')) {

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($careers_document))
                    {
                        $inserted_id = 0;
                        
                        $edit_record_id = $request->hid_record_id;
                        $lastest_id = $request->lastest_id;

                        if($edit_record_id > 0) {
                            $inserted_id = $edit_record_id;
                        }else{
                            $inserted_id = $lastest_id + 1;
                        }

                        $result = StorageHelper::store($path = 'public/careers/document/'.$inserted_id, array($careers_document), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $careers_document_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $careers_document_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$careers_document_path,
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
        $careers_data = CareersInfo::all();
        $careers = [];
        $banner = '';
        $blogs = CareersBlog::all();

        if (!$careers_data->isEmpty()){
            $careers = $careers_data->first();
            $banner = $careers->banner_image;
        }

        $data = [
                'careers_data' => $careers_data,
                'careers' => $careers,
                'banner' => $banner,
                'blogs' => $blogs
            ];

        return view('careers::info_index',$data);
    }

    public function editcontent()
    {
        $careers_data = CareersInfo::all();
        $careers = [];
        $banner = '';
        $blog_count = 0;
        $blogs = CareersBlog::all();
        $latest_id = 0;
        $id_array = [];

        if (!$careers_data->isEmpty()){
            $careers = $careers_data->first();
            $banner = $careers->banner_image;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('careers_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'careers_data' => $careers_data,
                'careers' => $careers,
                'hide_careers_image_ids' => $banner,
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count
            ];

        return view('careers::edit_info_index',$data);
    }
    
    public function banner_image_upload(Request $request)
    {
        try{
            if ($careers_image = $request->file('careers_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $filepath = '';

                    if(isset($careers_image))
                    {
                        $result = StorageHelper::store($path = 'public/careers/info', array($careers_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $filepath = $result[0]['data'];
                        $careers_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $careers_image_path,
                        ];
                    }
                }
                
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$careers_image_path,
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
        $careers_data = CareersInfo::all();

        $action = '';
        if (!$careers_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packInfoData($request);

            if($action == 'save') {
                $result = CareersInfo::create($payload);
            }else{
                $this->careersInfoRepository->update(1, $payload, true);
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
                        $careers_info_blog = CareersBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->careersBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->careersBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Careers Main Successfully'));
            return redirect(route('careers.careerss.infoIndex')); 
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packInfoData($request)
    {
        $payload['banner_image'] = $request->hide_careers_image_ids;
        $payload['caption'] = $request->caption;

        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->title_blog;
       
        return $payload;
    }
}
