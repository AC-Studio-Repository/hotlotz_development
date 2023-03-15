<?php

namespace App\Modules\Glossary\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\Glossary\Http\Requests\StoreGlossaryRequest;
use App\Modules\Glossary\Http\Requests\UpdateGlossaryRequest;
use App\Modules\Glossary\Http\Requests\AjaxCreateContentManagement;
use App\Modules\Glossary\Http\Repositories\GlossaryRepository;
use App\Modules\Glossary\Http\Repositories\GlossaryInfoRepository;
use App\Modules\Glossary\Models\Glossary;
use DB;
use Response;
use App\Helpers\NHelpers;
use App\Models\GeneralInfo;
use App\Modules\Glossary\Models\GlossaryInfo;
use App\Helpers\StorageHelper;


class GlossaryController extends BaseController
{
    protected $glossaryRepository;
    protected $glossaryInfoRepository;
    public function __construct(GlossaryRepository $glossaryRepository, GlossaryInfoRepository $glossaryInfoRepository){
        $this->glossaryRepository = $glossaryRepository;
        $this->glossaryInfoRepository = $glossaryInfoRepository;

        $upload_info_dir = storage_path('app/public/glossary_info/');
        NHelpers::dir_exists($upload_info_dir);
        $this->upload_info_dir = $upload_info_dir;
    }

    public function listpage()
    {
         return view('glossary::list');   
    }

    public function index()
    {
//         Machine::with(['reading' => function ($query) use ($date) {
//     $query->where('date', '>=', $date);
// }])->find($machineId);

        $glossarys = $this->glossaryRepository->all(['glossarycategory' => function ($query) {
    $query->orderBy('id', 'DESC');
}], false, 10);

        $data = [
            'glossarys' => $glossarys,
        ];
        return view('glossary::index',$data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $glossary = app(Glossary::class);
        $glossary_categories = DB::table('glossary_category')->pluck('name','id');
        
        $data = [
            'glossary' => $glossary,
            'glossary_categories' => $glossary_categories,
        ];
        return view('glossary::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGlossaryRequest $request)
    {
        try {
            $glossary = Glossary::create($this->packData($request));
            flash()->success(__(':name has been created', ['name' => $glossary->question]));
            return redirect(route('glossary.glossarys.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the Glossary
     *
     * @param Item $Glossary
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Glossary $glossary)
    {
        return view('glossary::show', [
            'glossary' => $glossary
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Glossary $glossary)
    {
        $glossary = $this->glossaryRepository->show('id', $glossary->id, [], true);
        $glossary_categories = DB::table('glossary_category')->pluck('name','id');
        
        $data = [
            'glossary' => $glossary,
            'glossary_categories' => $glossary_categories
        ];
        return view('glossary::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGlossaryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->glossaryRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => Glossary::find($id)->question]));
            return redirect()->route('glossary.glossarys.index')->with('success', 'Glossary Category Updated Successfully!');
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
                $this->glossaryRepository->destroy($id);
                DB::commit();

                return redirect()->route('glossary.glossarys.index')->with('success', 'Glossary Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('glossary.glossarys.index')->with('fail', 'Glossary Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->glossaryRepository->restore($id);
            DB::commit();

            return redirect()->route('glossary.glossarys.index')->with('success', 'Glossary Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('glossary.glossarys.index')->with('fail', 'Glossary Activating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['glossary_category_id'] = $request->category;
        $payload['question'] = $request->question;
        $payload['answer'] = $request->answer;
       
        return $payload;
    }

    public function infopage()
    {
        $banner = '';
        $glossary_data = GlossaryInfo::all();
        $glossary = [];

        if (!$glossary_data->isEmpty()){
            $glossary = $glossary_data->first();
            $banner = $glossary->banner_image;
        }

        $data = [
                'glossary_data' => $glossary_data,
                'glossary' => $glossary,
                'banner' => $banner
            ];

        return view('glossary::info_index',$data);
    }

    public function editcontent()
    {
        $banner = '';
        $glossary_data = GlossaryInfo::all();
        $glossary = [];

        if (!$glossary_data->isEmpty()){
            $glossary = $glossary_data->first();
            $banner = $glossary->banner_image;
        }

        $data = [
                'glossary_data' => $glossary_data,
                'glossary' => $glossary,
                'hide_glossary_image_ids' => '',
                'banner' => $banner
            ];

        return view('glossary::edit_content',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($glossary_banner_image = $request->file('glossary_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($glossary_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/glossary_info', array($glossary_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $glossary_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $glossary_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$glossary_banner_image_path,
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
        $glossary_data = GlossaryInfo::all();

        $action = '';
        if (!$glossary_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packInfoData($request);

            if($action == 'save') {
                $result = GlossaryInfo::create($payload);
            }else{
                $this->glossaryInfoRepository->update(1, $payload, true);
            }
            DB::commit();
           
           return response()->json(array('status' => '1','message'=>'Update Glossary Successfully.')); 
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    protected function packInfoData($request)
    {
        $payload['banner_image'] = $request->banner_image;
        $payload['caption'] = $request->caption;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->blog_1;
       
        return $payload;
    }
}
