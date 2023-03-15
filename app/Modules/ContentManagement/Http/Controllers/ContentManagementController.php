<?php

namespace App\Modules\ContentManagement\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\ContentManagement\Http\Requests\StoreContentManagement;
use App\Modules\ContentManagement\Http\Requests\UpdateContentManagement;
use App\Modules\ContentManagement\Http\Requests\AjaxCreateContentManagement;
use App\Modules\ContentManagement\Http\Repositories\TermsAndConditionsRepository;
use App\Modules\ContentManagement\Models\TermsAndConditions;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;


class ContentManagementController extends BaseController
{
    protected $termsAndConditionsRepository;
    public function __construct(TermsAndConditionsRepository $termsAndConditionsRepository){
        $this->termsAndConditionsRepository = $termsAndConditionsRepository;
    }

    public function index()
    {
        return view('content_management::index');

        // $content = "<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>";
        // <img onclick="imagepreview(this)" lazyload="on" src='https://picsum.photos/200'/>";

        // return view('content_management::index', [
        //     'content_managements' => $content
        // ]);
    }

    public function ajaxRequestPost(Request $request)
    {
        try {
            if(isset($request->content_str) && strlen($request->content_str) > 2){
            DB::beginTransaction();
            try {
                    $payload = [];
                    $payload['value'] = $request->content_str;

                    $result = $this->termsAndConditionsRepository->update(1, $payload, true);
                    if($result){
                        DB::commit();
                        return response()->json(array('status' => '1','message'=>'Update Content Successfully.'));
                    }else{
                        DB::rollback();
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    // return redirect()->back()->withInput();
                    return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
                }
            }
        } catch (Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function displayContent()
    {
        $termsandconditions_data = TermsAndConditions::pluck('value')->all();

        if(count($termsandconditions_data) > 0)
        {
            $termsandconditions_data = $termsandconditions_data[0];
        }else{
            $termsandconditions_data = "";
        }

        $data = [
            'content_managements' => $termsandconditions_data,
        ];
        return view('content_management::displayTandC',$data);
    }

    public function editcontent()
    {
        $termsandcondition = TermsAndConditions::first();
        // dd($termsandcondition);

        $terms_id = 0;
        if($termsandcondition)
        {
            $terms_id = $termsandcondition->id;
            $document_datas = $this->termsAndConditionsRepository->getTermsDocumentData($terms_id);
        }

        $data = [
            'termsandcondition' => $termsandcondition,
            'terms_id' => $terms_id,
            'initialpreview' => ($document_datas)?$document_datas['initialpreview']:null,
            'initialpreviewconfig' => ($document_datas)?$document_datas['initialpreviewconfig']:null,
        ];
        return view('content_management::edit',$data);
    }

    public function updateContent(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            try {
                    $payload = [];
                    $payload['value'] = $request->content;
                    $payload['file_path'] = $request->hide_file_path;
                    $payload['full_path'] = $request->hide_full_path;

                    $result = $this->termsAndConditionsRepository->update(1, $payload, true);
                    if($result){
                        DB::commit();

                        flash()->success(__('Content has been updated'));
                    }else{
                        DB::rollback();
                    }
                    return redirect()->route('content_management.termsandconditions.displayContentTandC')->with('success', 'Content Updated Successfully!');
                } catch (\Exception $e) {
                    DB::rollback();
                    flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                    return redirect()->back()->withInput();
                }
        } catch (Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }


    public function documentUpload($id, Request $request)
    {
        // ini_set('memory_limit', '2048M');
        try {
            // dd( $request->all() );
            if ($term_doc = $request->file('term_doc')) {

                $p1 = [];
                $p2 = [];
                $file_path = '';
                $full_path = '';

                if (isset($term_doc)) {
                    $terms_id = 1;
                    if ($id != '0') {
                        $terms_id = $id;
                    }

                    $file_path = Storage::put('terms_and_condition/'.$terms_id, $term_doc);
                    $file_name = $term_doc->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $key = '<code to parse your document key>';
                    $url = '/manage/termsandconditions/'.$terms_id.'/document_delete';
                    $p1[] = $full_path; // sends the data
                    $p2[] = [
                        'caption' => $file_name,
                        'url' => $url, 'key' => $terms_id,
                        'extra' => ['_token'=>csrf_token()]
                    ];
                }

                $data = [
                    'status'=>1,
                    'saved_file_path'=>$file_path,
                    'saved_full_path'=>$full_path,
                    'initialPreview' => $p1,
                    'initialPreviewConfig' => $p2,
                    'append' => true // whether to append these configurations to initialPreview.
                                     // if set to false it will overwrite initial preview
                                     // if set to true it will append to initial preview
                                     // if this propery not set or passed, it will default to true.
                ];

                return json_encode($data);
            }
        } catch (Exception $e) {
            return json_encode(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function documentDelete(Request $request, $id)
    {
        try {
            if ($id) {
                $termsandcondition = TermsAndConditions::find($id);
                if($termsandcondition){
                    Storage::delete($termsandcondition->file_path);
                    $payload = [
                        'file_path' => null,
                        'full_path' => null,
                    ];
                    $this->termsAndConditionsRepository->update($id, $payload, true);

                    return response()->json(array('status'=>1,'message'=>'Document Delete successfully!'));
                }
                return response()->json(array('status'=>-1,'message'=>'Document Delete failed! Terms and Conditions does not exist in Our System'));
            }
            return response()->json(array('status'=>-1,'message'=>'Document Delete failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }
}
