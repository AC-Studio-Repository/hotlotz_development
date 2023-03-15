<?php

namespace App\Modules\Policy\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Modules\Policy\Http\Requests\StorePolicyRequest;
use App\Modules\Policy\Http\Requests\UpdatePolicyRequest;
use App\Modules\Policy\Models\Policy;
use App\Modules\Policy\Http\Repositories\PolicyRepository;
use Illuminate\Support\Facades\Storage;

class PolicyController extends BaseController
{
    protected $policyRepository;
    public function __construct(PolicyRepository $policyRepository){
        $this->policyRepository = $policyRepository;
    }

    public function index()
    {
        $policies = $this->policyRepository->all([], false, 10);

        $data = [
            'policies' => $policies,
        ];
        return view('policy::index',$data);
    }

    public function showlist()
    {
        return view('policy::showlist');
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $policy = app(Policy::class);
        $hide_policy_doc_ids = '';
        $lastest_id = 0;

        $lastest_data = DB::table('policies')->latest('id')->first();
        if($lastest_data)
        {
           $lastest_id = $lastest_data->id;
        }

        $data = [
            'policy' => $policy,
            'policy_id' => 0,
            'lastest_id' => $lastest_id,
        ];
        return view('policy::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePolicyRequest $request)
    {
        try {
            $policy = Policy::create($this->packData($request));
            flash()->success(__(':name has been created', ['name' => $policy->menu_name]));
            return redirect(route('policy.policies.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the faq category
     *
     * @param Item $policy
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Policy $policy)
    {
        return view('policy::show', [
            'policy' => $policy
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Policy $policy)
    {
        $policy = $this->policyRepository->show('id', $policy->id, [], true);
        $lastest_id = DB::table('policies')->latest('id')->first();
        $document_datas = $this->policyRepository->getPolicyDocumentData($policy->id);
        
        $data = [
            'policy' => $policy,
            'policy_id' => $policy->id,
            'lastest_id' => $lastest_id->id,
            'initialpreview' => $document_datas['initialpreview'],
            'initialpreviewconfig' => $document_datas['initialpreviewconfig'],
        ];
        return view('policy::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePolicyRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->policyRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => Policy::find($id)->menu_name]));
            return redirect()->route('policy.policies.index')->with('success', ' Policy Updated Successfully!');
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
                $this->policyRepository->destroy($id);
                DB::commit();
                
                return redirect()->route('policy.policies.index')->with('success', ' Policy Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('policy.policies.index')->with('fail', 'FAQ Category Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->policyRepository->restore($id);
            DB::commit();

            return redirect()->route('policy.policies.index')->with('success', 'Policy Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('policy.policies.index')->with('fail', 'Policy Activating Failed!');
        }
    }

    public function policyDocumentUploadOld(Request $request)
    {
        try{
            if ($policy_document = $request->file('policy_document')) {

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($policy_document))
                    {
                        $inserted_id = 0;
                        
                        $edit_record_id = $request->hid_record_id;
                        $lastest_id = $request->lastest_id;

                        if($edit_record_id > 0) {
                            $inserted_id = $edit_record_id;
                        }else{
                            $inserted_id = $lastest_id + 1;
                        }

                        $result = StorageHelper::store($path = 'public/policy_document/'.$inserted_id, array($policy_document), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $policy_document_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $policy_document_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$policy_document_path,
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

    public function policyDocumentUpload($id, Request $request)
    {
        // ini_set('memory_limit', '2048M');
        try {
            if ($policy_document = $request->file('policy_document')) {

                $p1 = [];
                $p2 = [];
                $file_path = '';
                $full_path = '';

                if (isset($policy_document)) {
                    // $policy_id = 1;
                    // if ($id != '0') {
                    //     $policy_id = $id;
                    // }

                    $lastest_id = $request->lastest_id;
                    if($id > 0) {
                        $policy_id = $id;
                    }else{
                        $policy_id = $lastest_id + 1;
                    }

                    $file_path = Storage::put('policy/'.$policy_id, $policy_document);
                    $file_name = $policy_document->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $key = '<code to parse your document key>';
                    $url = '/manage/policies/'.$policy_id.'/document_delete';
                    $p1[] = $full_path; // sends the data
                    $p2[] = [
                        'caption' => $file_name,
                        'url' => $url, 'key' => $policy_id,
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

    public function policyDocumentDelete(Request $request, $id)
    {
        try {
            if ($id) {
                $policy = Policy::find($id);
                if($policy){
                    Storage::delete($policy->file_path);
                    $payload = [
                        'file_path' => null,
                        'full_path' => null,
                    ];
                    $this->policyRepository->update($id, $payload, true);

                    return response()->json(array('status'=>1,'message'=>'Document Delete successfully!'));
                }
                return response()->json(array('status'=>-1,'message'=>'Document Delete failed! This Policy does not exist in Our System'));
            }
            return response()->json(array('status'=>-1,'message'=>'Document Delete failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    protected function packData($request)
    {
        $payload['menu_name'] = $request->menu_name;
        $payload['title'] = $request->title;
        $payload['content'] = $request->content;
        // $payload['file_path'] = $request->hide_policy_doc_ids;
        $payload['file_path'] = $request->hide_file_path;
        $payload['full_path'] = $request->hide_full_path;
       
        return $payload;
    }
}
