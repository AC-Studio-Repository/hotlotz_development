<?php

namespace App\Modules\Faq\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\Faq\Http\Requests\StoreFaqRequest;
use App\Modules\Faq\Http\Requests\StoreFaqInfoRequest;
use App\Modules\Faq\Http\Requests\UpdateFaqRequest;

use App\Modules\Faq\Models\Faq;
use App\Modules\Faq\Models\FaqInfo;

use App\Modules\Faq\Http\Repositories\FaqRepository;
use App\Modules\Faq\Http\Repositories\FaqInfoRepository;

class FaqController extends BaseController
{
    protected $faqRepository;
    protected $faqInfoRepository;
    public function __construct(FaqRepository $faqRepository, FaqInfoRepository $faqInfoRepository){
        $this->faqRepository = $faqRepository;
        $this->faqInfoRepository = $faqInfoRepository;
    }

    public function index()
    {
        $faqs = $this->faqRepository->all(['faqcategory'], false, 10);
        // $faqs = Faq::with('faqcategory')->get();
        // dd($faqs);
        $data = [
            'faqs' => $faqs,
        ];
        return view('faq::index',$data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faq = app(Faq::class);
        $faq_categories = DB::table('faq_categories')->pluck('name','id');
        
        $data = [
            'faq' => $faq,
            'faq_categories' => $faq_categories,
        ];
        return view('faq::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFaqRequest $request)
    {
        try {
            $faq = Faq::create($this->packData($request));
            flash()->success(__(':name has been created', ['name' => $faq->question]));
            return redirect(route('faq.faqs.index'));
            
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
    public function show(Faq $faq)
    {
        return view('faq::show', [
            'faq' => $faq
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        $faq = $this->faqRepository->show('id', $faq->id, [], true);
        $faq_categories = DB::table('faq_categories')->pluck('name','id');
        
        $data = [
            'faq' => $faq,
            'faq_categories' => $faq_categories
        ];
        return view('faq::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFaqRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->faqRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => Faq::find($id)->question]));
            return redirect()->route('faq.faqs.index')->with('success', 'FAQ Category Updated Successfully!');
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
                $this->faqRepository->destroy($id);
                DB::commit();

                return redirect()->route('faq.faqs.index')->with('success', 'FAQ Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('faq.faqs.index')->with('fail', 'FAQ Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->faqRepository->restore($id);
            DB::commit();

            return redirect()->route('faq.faqs.index')->with('success', 'FAQ Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('faq.faqs.index')->with('fail', 'FAQ Activating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['faq_category_id'] = $request->category;
        $payload['question'] = $request->question;
        $payload['answer'] = $request->answer;
       
        return $payload;
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($faq_info_image = $request->file('faq_info_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($faq_info_image)){

                        $result = StorageHelper::store($path = 'public/faq_info', array($faq_info_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $faq_info_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $faq_info_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$faq_info_image_path,
                    'saved_storage_filepath' => $faq_info_image_path,
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
        $faq_info_data = FaqInfo::all();
        $faq_info = [];
        $banner = '';

        if (!$faq_info_data->isEmpty()){
            $faq_info = $faq_info_data->first();
            $banner = $faq_info->banner_image;
        }

        $data = [
                'faq_info_data' => $faq_info_data,
                'faq_info' => $faq_info,
                'banner' => $banner
            ];

        return view('faq::info_index',$data);
    }

    public function editcontent()
    {
        $faq_info_data = FaqInfo::all();
        $faq_info = [];
        $banner = '';

        if (!$faq_info_data->isEmpty()){
            $faq_info = $faq_info_data->first();
            $banner = $faq_info->banner_image;
        }

        $data = [
                'faq_info_data' => $faq_info_data,
                'faq_info' => $faq_info,
                'hide_faq_info_image_ids' => $banner,
                'banner' => $banner
            ];

        return view('faq::edit_info_index',$data);
    }

    public function updateContent(Request $request)
    {
        $faq_info_data = FaqInfo::all();

        $action = '';
        if (!$faq_info_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packInfoData($request);

            if($action == 'save') {
                $result = FaqInfo::create($payload);
            }else{
                $this->faqInfoRepository->update(1, $payload, true);
            }
            DB::commit();

           flash()->success(__('Update FAQ Main Successfully'));
            return redirect(route('faq.faqs.infoIndex')); 
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packInfoData($request)
    {
        $payload['banner_image'] = $request->hide_faq_info_image_ids;
        $payload['caption'] = $request->caption;

        $payload['title_header'] = $request->title_header;
        $payload['title_blog'] = $request->title_blog;
       
        return $payload;
    }
}
