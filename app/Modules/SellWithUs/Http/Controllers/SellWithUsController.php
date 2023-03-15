<?php

namespace App\Modules\SellWithUs\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\SellWithUs\Http\Requests\StoreSellWithUsRequest;
use App\Modules\SellWithUs\Http\Requests\UpdateSellWithUsRequest;
use App\Modules\SellWithUs\Http\Repositories\SellWithUsBlogRepository;
use App\Modules\SellWithUs\Http\Repositories\SellWithUsFaqRepository;
use App\Modules\SellWithUs\Models\SellWithUsBlog;
use App\Modules\SellWithUs\Models\SellWithUsFaq;
use DB;
use Response;
use App\Helpers\NHelpers;
use App\Models\GeneralInfo;
use App\Modules\SellWithUs\Http\Requests\StoreSellWithUsInfoRequest;
use App\Helpers\StorageHelper;


class SellWithUsController extends BaseController
{
    protected $sellWithUsBlogRepository;
    protected $sellWithUsFaqRepository;
    public function __construct(SellWithUsBlogRepository $sellWithUsBlogRepository, SellWithUsFaqRepository $sellWithUsFaqRepository){
        
        $this->sellWithUsBlogRepository = $sellWithUsBlogRepository;
        $this->sellWithUsFaqRepository = $sellWithUsFaqRepository;

        $upload_info_dir = storage_path('app/public/sell_with_us_info/');
        NHelpers::dir_exists($upload_info_dir);
        $this->upload_info_dir = $upload_info_dir;
    }

    public function index()
    {
        return view('sell_with_us::index');
    }

    public function list()
    {
        $sell_with_us_data = $this->sellWithUsFaqRepository->all([], false, 10);

        $data = [
            'sell_with_us_data' => $sell_with_us_data,
        ];
        return view('sell_with_us::list',$data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sell_with_us = app(SellWithUsFaq::class);
        
        $data = [
            'sell_with_us' => $sell_with_us,
        ];
        return view('sell_with_us::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellWithUsRequest $request)
    {
        try {
            $sell_with_us = SellWithUsFaq::create($this->packData($request));
            flash()->success(__('Sell With Us FAQ has been created'));
            return redirect(route('sell_with_us.sell_with_uss.list'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the SellWithUs
     *
     * @param Item $sellWithUs
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(SellWithUsFaq $sell_with_uss)
    {
        return view('sell_with_us::show', [
            'sell_with_us' => $sell_with_uss
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SellWithUsFaq $sell_with_uss)
    {
        $sell_with_uss = $this->sellWithUsFaqRepository->show('id', $sell_with_uss->id, [], true);
        
        $data = [
            'sell_with_us' => $sell_with_uss
        ];
        return view('sell_with_us::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSellWithUsRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->sellWithUsFaqRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => SellWithUsFaq::find($id)->question]));
            return redirect()->route('sell_with_us.sell_with_uss.list')->with('success', 'SellWithUs Updated Successfully!');
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
                $this->sellWithUsFaqRepository->destroy($id);
                DB::commit();

                $flash_message = 'SellWithUs Deactivated Successfully!';
                    return redirect()->route('sell_with_us.sell_with_uss.list')->withDelete($flash_message);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sell_with_us.sell_with_uss.list')->with('fail', 'SellWithUs Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->sellWithUsFaqRepository->restore($id);
            DB::commit();

            return redirect()->route('sell_with_us.sell_with_uss.list')->with('success', 'SellWithUs Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sell_with_us.sell_with_uss.list')->with('fail', 'SellWithUs Activating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['question'] = $request->question;
        $payload['answer'] = $request->answer;
       
        return $payload;
    }

    public function infopage()
    {
        $sell_with_us_data = SellWithUsBlog::all();
        $sell_with_us = [];
        $banner = '';

        if (!$sell_with_us_data->isEmpty()){
            $sell_with_us = $sell_with_us_data->first();
            $banner = $sell_with_us->banner_image;
        }

        $data = [
                'sell_with_us_data' => $sell_with_us_data,
                'sell_with_us' => $sell_with_us,
                'banner' => $banner
            ];

        return view('sell_with_us::info_index',$data);
    }

    public function editcontent()
    {
        $sell_with_us_data = SellWithUsBlog::all();
        $sell_with_us = [];
        $banner = '';

        if (!$sell_with_us_data->isEmpty()){
            $sell_with_us = $sell_with_us_data->first();
            $banner = $sell_with_us->banner_image;
        }

        $data = [
                'sell_with_us_data' => $sell_with_us_data,
                'sell_with_us' => $sell_with_us,
                'hide_sellwithus_image_ids' => '',
                'banner' => $banner
            ];

        return view('sell_with_us::edit_content',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($sellwithus_banner_image = $request->file('sellwithus_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($sellwithus_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/sell_with_us_info', array($sellwithus_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $sellwithus_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $sellwithus_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$sellwithus_banner_image_path,
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
        $sell_with_us_data = SellWithUsBlog::all();

        $action = '';
        if (!$sell_with_us_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packInfoData($request);

            if($action == 'save') {
                $result = SellWithUsBlog::create($payload);
            }else{
                $this->sellWithUsBlogRepository->update(1, $payload, true);
            }
            DB::commit();
           
           return response()->json(array('status' => '1','message'=>'Update Sell With Us Successfully.')); 
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    protected function packInfoData($request)
    {
        $payload['banner_image'] = $request->banner_image;
        $payload['blog_header_1'] = $request->blog_header_1;
        $payload['blog_1'] = $request->blog_1;
        $payload['blog_header_2'] = $request->blog_header_2;
        $payload['blog_2'] = $request->blog_2;
       
        return $payload;
    }
}
