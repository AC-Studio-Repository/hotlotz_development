<?php

namespace App\Modules\WhatWeSell\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use App\Models\GeneralInfo;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\WhatWeSell\Http\Requests\StoreWhatWeSellRequest;
use App\Modules\WhatWeSell\Http\Requests\UpdateWhatWeSellRequest;
use App\Modules\WhatWeSell\Http\Requests\StoreWhatWeSellInfoRequest;

use App\Modules\Category\Models\Category;
use App\Modules\WhatWeSell\Models\WhatWeSell;
use App\Modules\WhatWeSell\Models\WhatWeSellBlog;
use App\Modules\OurTeam\Models\OurTeam;

use App\Modules\WhatWeSell\Http\Repositories\WhatWeSellRepository;
use App\Modules\WhatWeSell\Http\Repositories\WhatWeSellBlogRepository;

class WhatWeSellController extends BaseController
{
    protected $whatWeSellRepository;
    protected $whatWeSellBlogRepository;
    public function __construct(WhatWeSellRepository $whatWeSellRepository, WhatWeSellBlogRepository $whatWeSellBlogRepository){
        $this->whatWeSellRepository = $whatWeSellRepository;
        $this->whatWeSellBlogRepository = $whatWeSellBlogRepository;
    }

    public function showlist()
    {
        return view('what_we_sell::showlist');
    }

    public function index()
    {
        $whatWeSells = $this->whatWeSellRepository->all([], false, 10);

        $data = [
            'whatWeSells' => $whatWeSells
        ];
        return view('what_we_sell::index',$data);
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
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $key_contact_1 = 0;
        $key_contact_2 = 0;

        $whatWeSell = app(WhatWeSell::class);
        $categories = Category::where('parent_id',null)->pluck('name','id')->all();
        $what_we_sell_data = WhatWeSell::all();
        if (!$what_we_sell_data->isEmpty()){
            $lastest_data = DB::table('what_we_sell')->latest('id')->first();
            $lastest_id = $lastest_data->id;
        }

        $data = [
            'whatwesell' => $whatWeSell,
            'hide_whatwesell_ids' => '',
            'hide_banner_whatwesell_ids' => '',
            'hide_whatwesell_detail_1_ids' => '',
            'hide_whatwesell_detail_2_ids' => '',
            'hide_whatwesell_detail_3_ids' => '',
            'hide_whatwesell_detail_4_ids' => '',
            'hide_whatwesell_detail_5_ids' => '',
            'hide_whatwesell_detail_6_ids' => '',
            'hide_whatwesell_detail_7_ids' => '',
            'hide_whatwesell_detail_8_ids' => '',
            'hide_whatwesell_detail_9_ids' => '',
            'hide_whatwesell_detail_10_ids' => '',
            'hide_whatwesell_detail_11_ids' => '',
            'hide_whatwesell_detail_12_ids' => '',
            'banner' => $banner,
            'hide_image_ids' => '',
            'categories' => $categories,
            'hid_record_id' => $hid_record_id,
            'lastest_id' => $lastest_id,
            'blog_count' => 0,
            'blog_latest_id' => 0,
            'status' => 'create',
            'hide_key_contact_image_ids' => '',
            'ourteam' => $ourteam,
            'key_contact_1' => $key_contact_1,
            'key_contact_2' => $key_contact_2
        ];

        return view('what_we_sell::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWhatWeSellRequest $request)
    {
        try {
            $whatWeSell = WhatWeSell::create($this->packData($request));
            $what_we_sell_id = $whatWeSell->id;

            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {
                if(isset($request['title_'.$i]))
                {
                    $payload['title'] = $request['title_'.$i];
                    $payload['blog'] = $request['blog_'.$i];
                    $payload['what_we_sell_id'] = $what_we_sell_id;

                    //create
                    $what_we_sell_blog = WhatWeSellBlog::create($payload);
                }
            }

            flash()->success(__('WhatWeSell has been created'));
            return redirect(route('whatwesell.whatwesells.index'));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the WhatWeSell
     *
     * @param Item $WhatWeSell
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(WhatWeSell $whatwesell)
    {
        $key_contact_1 = '';
        $key_contact_2 = '';

        if($whatwesell->key_contact_1 != 0)
        {
            $key_contact_data = OurTeam::where('id', '=', $whatwesell->key_contact_1)->first();
            $key_contact_1 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
        }
        if($whatwesell->key_contact_2 != 0)
        {
            $key_contact_data = OurTeam::where('id', '=', $whatwesell->key_contact_2)->first();
            $key_contact_2 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
        }
        return view('what_we_sell::show', [
            'whatwesell' => $whatwesell,
            'key_contact_1' => $key_contact_1,
            'key_contact_2' => $key_contact_2
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(WhatWeSell $whatwesell)
    {
        $banner = '';
        $id_array = [];
        $hid_record_id = 0;
        $what_we_sell_edit_id = $whatwesell->id;
        $whatWeSell = $this->whatWeSellRepository->show('id', $whatwesell->id, [], true);
        $categories = Category::where('parent_id',null)->pluck('name','id')->all();
        $lastest_id = DB::table('what_we_sell')->latest('id')->first();
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $key_contact_1 = 0;
        $key_contact_2 = 0;
        $blog_count = 0;
        $blog_latest_id = 0;
        $blogs = WhatWeSellBlog::where('what_we_sell_id', '=', $what_we_sell_edit_id)->get();

        if(!$blogs->isEmpty())
        {
            $blog_count = $blogs->count();

            $blog_latest_data = DB::table('what_we_sell_blogs')->where('what_we_sell_id', $what_we_sell_edit_id)->orderBy('id', 'DESC')->first();
            $blog_latest_id = $blog_latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }

        $data = [
            'whatwesell' => $whatWeSell,
            'hide_whatwesell_ids' => $whatWeSell->list_image_file_path,
            'hide_banner_whatwesell_ids' => $whatWeSell->list_banner_image_file_path,
            'hide_whatwesell_detail_1_ids' => $whatWeSell->detail_image_1_file_path,
            'hide_whatwesell_detail_2_ids' => $whatWeSell->detail_image_2_file_path,
            'hide_whatwesell_detail_3_ids' => $whatWeSell->detail_image_3_file_path,
            'hide_whatwesell_detail_4_ids' => $whatWeSell->detail_image_4_file_path,
            'hide_whatwesell_detail_5_ids' => $whatWeSell->detail_image_5_file_path,
            'hide_whatwesell_detail_6_ids' => $whatWeSell->detail_image_6_file_path,
            'hide_whatwesell_detail_7_ids' => $whatWeSell->detail_image_7_file_path,
            'hide_whatwesell_detail_8_ids' => $whatWeSell->detail_image_8_file_path,
            'hide_whatwesell_detail_9_ids' => $whatWeSell->detail_image_9_file_path,
            'hide_whatwesell_detail_10_ids' => $whatWeSell->detail_image_10_file_path,
            'hide_whatwesell_detail_11_ids' => $whatWeSell->detail_image_11_file_path,
            'hide_whatwesell_detail_12_ids' => $whatWeSell->detail_image_12_file_path,
            'hide_key_contact_image_ids' => $whatWeSell->key_contact_image,
            'categories' => $categories,
            'hid_record_id' => $whatWeSell->id,
            'lastest_id' => $lastest_id->id,
            'blog_count' => $blog_count,
            'blog_latest_id' => $blog_latest_id,
            'blogs' => $blogs,
            'status' => 'edit',
            'id_array' => $id_array,
            'ourteam' => $ourteam,
            'key_contact_1' => $whatWeSell->key_contact_1,
            'key_contact_2' => $whatWeSell->key_contact_2
        ];

        return view('what_we_sell::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWhatWeSellRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->whatWeSellRepository->update($id, $payload, true);
            DB::commit();

            for($i = 1; $i <= $request->hid_backend_count; $i++)
            {
                if(isset($request['title_'.$i]))
                {
                    $blog_payload['title'] = $request['title_'.$i];
                    $blog_payload['blog'] = $request['blog_'.$i];
                    $blog_payload['what_we_sell_id'] = $id;
                    $hid_edit_id = (int)$request['hid_edit_id_'.$i];
                    $hid_delete_id = (int)$request['hid_delete_id_'.$i];

                    if($hid_edit_id == 0 && $hid_delete_id == 0)
                    {
                        //create
                        $what_we_sell_blog = WhatWeSellBlog::create($blog_payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        WhatWeSellBlog::where('id', $hid_edit_id)
                            ->where('what_we_sell_id', $id)
                            ->delete();
                        //$this->whatWeSellBlogRepository->destroy($hid_delete_id);
                        DB::commit();

                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        WhatWeSellBlog::where('id', '=', $hid_edit_id)->where( 'what_we_sell_id', '=', $id)->update($blog_payload, true);

                        //$this->whatWeSellBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }

            flash()->success(__('What We Sell :title has been updated', ['name' => WhatWeSell::find($id)->title]));
            return redirect()->route('whatwesell.whatwesells.index')->with('success', 'WhatWeSell Updated Successfully!');
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
                $this->whatWeSellRepository->destroy($id);

                WhatWeSellBlog::where('what_we_sell_id', $id)
                            ->delete();

                DB::commit();

                return redirect()->route('whatwesell.whatwesells.index')->with('success', 'WhatWeSell Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('whatwesell.whatwesells.index')->with('fail', 'WhatWeSell Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->whatWeSellRepository->restore($id);
            DB::commit();

            return redirect()->route('whatwesell.whatwesells.index')->with('success', 'WhatWeSell Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('whatwesell.whatwesells.index')->with('fail', 'WhatWeSell Activating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['title'] = $request->title;
        $payload['list_image_file_path'] = $request->hide_whatwesell_ids;
        $payload['list_banner_image_file_path'] = $request->hide_whatwesell_banner_ids;
        $payload['category_id'] = $request->category_id;
        // $payload['price'] = $request->price;
        if($request->price_status) {
            $payload['price_status'] = $request->price_status;
        }else{
            $payload['price_status'] = 0;
        }

        // $payload['buyerlevel'] = $request->buyerlevel;
        $payload['caption'] = $request->caption;

        // if($request->description) {
        $payload['description'] = $request->description;

        $payload['detail_image_1_price_status'] = 1;
        if($request->detail_image_1_caption) {
            $payload['detail_image_1_caption'] = $request->detail_image_1_caption;
        }
        if($request->detail_image_1_title) {
            $payload['detail_image_1_title'] = $request->detail_image_1_title;
        }
        if($request->hide_whatwesell_detail_1_ids) {
            $payload['detail_image_1_file_path'] = $request->hide_whatwesell_detail_1_ids;
        }


        $payload['detail_image_2_price_status'] = 1;
        if($request->detail_image_2_caption) {
            $payload['detail_image_2_caption'] = $request->detail_image_2_caption;
        }
        if($request->detail_image_2_title) {
            $payload['detail_image_2_title'] = $request->detail_image_2_title;
        }
        if($request->hide_whatwesell_detail_2_ids) {
            $payload['detail_image_2_file_path'] = $request->hide_whatwesell_detail_2_ids;
        }


        if($request->detail_image_3_price) {
            $payload['detail_image_3_price'] = $request->detail_image_3_price;
        }
        $payload['detail_image_3_price_status'] = 1;
        if($request->detail_image_3_buyerlevel) {
            $payload['detail_image_3_buyerlevel'] = $request->detail_image_3_buyerlevel;
        }
        if($request->detail_image_3_caption) {
            $payload['detail_image_3_caption'] = $request->detail_image_3_caption;
        }
        if($request->detail_image_3_title) {
            $payload['detail_image_3_title'] = $request->detail_image_3_title;
        }
        if($request->hide_whatwesell_detail_3_ids) {
            $payload['detail_image_3_file_path'] = $request->hide_whatwesell_detail_3_ids;
        }


        if($request->detail_image_4_price) {
            $payload['detail_image_4_price'] = $request->detail_image_4_price;
        }
        $payload['detail_image_4_price_status'] = 1;
        if($request->detail_image_4_buyerlevel) {
            $payload['detail_image_4_buyerlevel'] = $request->detail_image_4_buyerlevel;
        }
        if($request->detail_image_4_caption) {
            $payload['detail_image_4_caption'] = $request->detail_image_4_caption;
        }
        if($request->detail_image_4_title) {
            $payload['detail_image_4_title'] = $request->detail_image_4_title;
        }
        if($request->hide_whatwesell_detail_4_ids) {
            $payload['detail_image_4_file_path'] = $request->hide_whatwesell_detail_4_ids;
        }


        if($request->detail_image_5_price) {
            $payload['detail_image_5_price'] = $request->detail_image_5_price;
        }
        $payload['detail_image_5_price_status'] = 1;
        if($request->detail_image_5_buyerlevel) {
            $payload['detail_image_5_buyerlevel'] = $request->detail_image_5_buyerlevel;
        }
        if($request->detail_image_5_caption) {
            $payload['detail_image_5_caption'] = $request->detail_image_5_caption;
        }
        if($request->detail_image_5_title) {
            $payload['detail_image_5_title'] = $request->detail_image_5_title;
        }
        if($request->hide_whatwesell_detail_5_ids) {
            $payload['detail_image_5_file_path'] = $request->hide_whatwesell_detail_5_ids;
        }

        if($request->detail_image_6_price) {
            $payload['detail_image_6_price'] = $request->detail_image_6_price;
        }
        $payload['detail_image_6_price_status'] = 1;
        if($request->detail_image_6_buyerlevel) {
            $payload['detail_image_6_buyerlevel'] = $request->detail_image_6_buyerlevel;
        }
        if($request->detail_image_6_caption) {
            $payload['detail_image_6_caption'] = $request->detail_image_6_caption;
        }
        if($request->detail_image_6_title) {
            $payload['detail_image_6_title'] = $request->detail_image_6_title;
        }
        if($request->hide_whatwesell_detail_6_ids) {
            $payload['detail_image_6_file_path'] = $request->hide_whatwesell_detail_6_ids;
        }


        if($request->detail_image_7_price) {
            $payload['detail_image_7_price'] = $request->detail_image_7_price;
        }
        $payload['detail_image_7_price_status'] = 1;
        if($request->detail_image_7_buyerlevel) {
            $payload['detail_image_7_buyerlevel'] = $request->detail_image_7_buyerlevel;
        }
        if($request->detail_image_7_caption) {
            $payload['detail_image_7_caption'] = $request->detail_image_7_caption;
        }
        if($request->detail_image_7_title) {
            $payload['detail_image_7_title'] = $request->detail_image_7_title;
        }
        if($request->hide_whatwesell_detail_7_ids) {
            $payload['detail_image_7_file_path'] = $request->hide_whatwesell_detail_7_ids;
        }


        if($request->detail_image_8_price) {
            $payload['detail_image_8_price'] = $request->detail_image_8_price;
        }
        $payload['detail_image_8_price_status'] = 1;
        if($request->detail_image_8_buyerlevel) {
            $payload['detail_image_8_buyerlevel'] = $request->detail_image_8_buyerlevel;
        }
        if($request->detail_image_8_caption) {
            $payload['detail_image_8_caption'] = $request->detail_image_8_caption;
        }
        if($request->detail_image_8_title) {
            $payload['detail_image_8_title'] = $request->detail_image_8_title;
        }
        if($request->hide_whatwesell_detail_8_ids) {
            $payload['detail_image_8_file_path'] = $request->hide_whatwesell_detail_8_ids;
        }


        if($request->detail_image_9_price) {
            $payload['detail_image_9_price'] = $request->detail_image_9_price;
        }
        $payload['detail_image_9_price_status'] = 1;
        if($request->detail_image_9_buyerlevel) {
            $payload['detail_image_9_buyerlevel'] = $request->detail_image_9_buyerlevel;
        }
        if($request->detail_image_9_caption) {
            $payload['detail_image_9_caption'] = $request->detail_image_9_caption;
        }
        if($request->detail_image_9_title) {
            $payload['detail_image_9_title'] = $request->detail_image_9_title;
        }
        if($request->hide_whatwesell_detail_9_ids) {
            $payload['detail_image_9_file_path'] = $request->hide_whatwesell_detail_9_ids;
        }


        if($request->detail_image_10_price) {
            $payload['detail_image_10_price'] = $request->detail_image_10_price;
        }
        $payload['detail_image_10_price_status'] = 1;
        if($request->detail_image_10_buyerlevel) {
            $payload['detail_image_10_buyerlevel'] = $request->detail_image_10_buyerlevel;
        }
        if($request->detail_image_10_caption) {
            $payload['detail_image_10_caption'] = $request->detail_image_10_caption;
        }
        if($request->detail_image_10_title) {
            $payload['detail_image_10_title'] = $request->detail_image_10_title;
        }
        if($request->hide_whatwesell_detail_10_ids) {
            $payload['detail_image_10_file_path'] = $request->hide_whatwesell_detail_10_ids;
        }


        if($request->detail_image_11_price) {
            $payload['detail_image_11_price'] = $request->detail_image_11_price;
        }
        $payload['detail_image_11_price_status'] = 1;
        if($request->detail_image_11_buyerlevel) {
            $payload['detail_image_11_buyerlevel'] = $request->detail_image_11_buyerlevel;
        }
        if($request->detail_image_11_caption) {
            $payload['detail_image_11_caption'] = $request->detail_image_11_caption;
        }
        if($request->detail_image_11_title) {
            $payload['detail_image_11_title'] = $request->detail_image_11_title;
        }
        if($request->hide_whatwesell_detail_11_ids) {
            $payload['detail_image_11_file_path'] = $request->hide_whatwesell_detail_11_ids;
        }


        if($request->detail_image_12_price) {
            $payload['detail_image_12_price'] = $request->detail_image_12_price;
        }
        $payload['detail_image_12_price_status'] = 1;
        if($request->detail_image_12_buyerlevel) {
            $payload['detail_image_12_buyerlevel'] = $request->detail_image_12_buyerlevel;
        }
        if($request->detail_image_12_caption) {
            $payload['detail_image_12_caption'] = $request->detail_image_12_caption;
        }
        if($request->detail_image_12_title) {
            $payload['detail_image_12_title'] = $request->detail_image_12_title;
        }
        if($request->hide_whatwesell_detail_12_ids) {
            $payload['detail_image_12_file_path'] = $request->hide_whatwesell_detail_12_ids;
        }

        if($request->key_contact_1 && $request->key_contact_1 != '')
        {
            $payload['key_contact_1'] = $request->key_contact_1;
        }
        if($request->key_contact_2 && $request->key_contact_2 != '')
        {
            $payload['key_contact_2'] = $request->key_contact_2;
        }

        return $payload;
    }

    public function info_banner_upload(Request $request)
    {
        try{
            if ($what_we_sell_image = $request->file('whatwesell_info_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($what_we_sell_image)){

                        $result = StorageHelper::store($path = 'public/what_we_sell/info', array($what_we_sell_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $what_we_sell_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $what_we_sell_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$what_we_sell_image_path,
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

    public function infopage()
    {
        $banner = '';
        $whatWeSell_info = GeneralInfo::all()->whereIn('key', ['whatwesell_info','whatwesell_banner']);

        $whatWeSell_info_array = [];
        foreach($whatWeSell_info as $key=>$value)
        {
            if($value->key == 'whatwesell_info')
                $whatWeSell_info_array[0] = $value->value;

            if($value->key == 'whatwesell_banner')
                $whatWeSell_info_array[1] = $value->value;
        }

        if($whatWeSell_info_array[1] != null)
        {
            $banner = $whatWeSell_info_array[1];
        }

        $data = [
            'whatwesell_info' => $whatWeSell_info_array,
            'hide_image_ids' => $banner,
            'banner' => $banner
        ];

        return view('what_we_sell::info_index',$data);
    }

    public function storeInfo(StoreWhatWeSellInfoRequest $request)
    {
        try {

            $info_value = $request->whatwesell_info_value;
            $banner_image = $request->hide_whatwesell_info_image_ids;

            $info_update = DB::table('general_info')->where('key', 'whatwesell_info')->update(['value' => $info_value]);

            $banner_update = DB::table('general_info')->where('key', 'whatwesell_banner')->update(['value' => $banner_image]);

            flash()->success(__('WhatWeSell Info has been saved'));
            return redirect(route('whatwesell.whatwesells.infopage'));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    public function imageUpload(Request $request)
    {
        try{
            if ($request->file('whatwesell_image') || $request->file('whatwesell_banner_image')) {
                $what_we_sell_image = '';
                $what_we_sell_image_folder_name = '';
                if($request->file('whatwesell_image')) {
                    $what_we_sell_image = $request->file('whatwesell_image');
                    $what_we_sell_image_folder_name = 'what_we_sell_image';
                }

                if($request->file('whatwesell_banner_image')) {
                    $what_we_sell_image = $request->file('whatwesell_banner_image');
                    $what_we_sell_image_folder_name = 'what_we_sell_banner';
                }

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                if(isset($what_we_sell_image))
                {
                    $inserted_id = 0;

                    $edit_record_id = $request->hid_record_id;
                    $lastest_id = $request->lastest_id;

                    if($edit_record_id > 0) {
                        $inserted_id = $edit_record_id;
                    }else{
                        $inserted_id = $lastest_id + 1;
                    }

                    $result = StorageHelper::store($path = 'public/what_we_sell/main/'.$what_we_sell_image_folder_name.'/'.$inserted_id, array($what_we_sell_image), $wipeExisting=true);

                    $filename = $result[0]['name'];
                    $what_we_sell_image_path = $result[0]['data'];

                    $insert_item_imgs = [
                        'file_name' => $filename,
                        'file_path' => $what_we_sell_image_path,
                    ];
                }
            }

            $data = [
                'status'=>1,
                'saved_filepath'=>$what_we_sell_image_path,
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

    public function detailImageUpload(Request $request)
    {
        try{
            if ($request->file('whatwesell_detail_image_1') || $request->file('whatwesell_detail_image_2') ||
             $request->file('whatwesell_detail_image_3') || $request->file('whatwesell_detail_image_4') ||
             $request->file('whatwesell_detail_image_5') || $request->file('whatwesell_detail_image_6') ||
             $request->file('whatwesell_detail_image_7') || $request->file('whatwesell_detail_image_8') ||
             $request->file('whatwesell_detail_image_9') || $request->file('whatwesell_detail_image_10') ||
             $request->file('whatwesell_detail_image_11') || $request->file('whatwesell_detail_image_12')
            ) {
                $what_we_sell_image = '';
                $what_we_sell_image_folder_name = '';
                if($request->file('whatwesell_detail_image_1')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_1');
                    $what_we_sell_image_folder_name = '1';
                }

                if($request->file('whatwesell_detail_image_2')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_2');
                    $what_we_sell_image_folder_name = '2';
                }

                if($request->file('whatwesell_detail_image_3')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_3');
                    $what_we_sell_image_folder_name = '3';
                }

                if($request->file('whatwesell_detail_image_4')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_4');
                    $what_we_sell_image_folder_name = '4';
                }

                if($request->file('whatwesell_detail_image_5')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_5');
                    $what_we_sell_image_folder_name = '5';
                }

                if($request->file('whatwesell_detail_image_6')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_6');
                    $what_we_sell_image_folder_name = '6';
                }

                if($request->file('whatwesell_detail_image_7')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_7');
                    $what_we_sell_image_folder_name = '7';
                }

                if($request->file('whatwesell_detail_image_8')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_8');
                    $what_we_sell_image_folder_name = '8';
                }

                if($request->file('whatwesell_detail_image_9')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_9');
                    $what_we_sell_image_folder_name = '9';
                }

                if($request->file('whatwesell_detail_image_10')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_10');
                    $what_we_sell_image_folder_name = '10';
                }

                if($request->file('whatwesell_detail_image_11')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_11');
                    $what_we_sell_image_folder_name = '11';
                }

                if($request->file('whatwesell_detail_image_12')) {
                    $what_we_sell_image = $request->file('whatwesell_detail_image_12');
                    $what_we_sell_image_folder_name = '12';
                }

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                if(isset($what_we_sell_image))
                {
                    $inserted_id = 0;

                    $edit_record_id = $request->hid_record_id;
                    $lastest_id = $request->lastest_id;

                    if($edit_record_id > 0) {
                        $inserted_id = $edit_record_id;
                    }else{
                        $inserted_id = $lastest_id + 1;
                    }

                    $result = StorageHelper::store($path = 'public/what_we_sell/detail/'.$inserted_id.'/'.$what_we_sell_image_folder_name, array($what_we_sell_image), $wipeExisting=true);

                    $filename = $result[0]['name'];
                    $what_we_sell_image_path = $result[0]['data'];

                    $insert_item_imgs = [
                        'file_name' => $filename,
                        'file_path' => $what_we_sell_image_path,
                    ];
                }
            }

            $data = [
                'status'=>1,
                'saved_filepath'=>$what_we_sell_image_path,
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

    public function key_contact_image_upload(Request $request)
    {
        try{
            if ($key_contact_image = $request->file('key_contact_image')) {

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($key_contact_image))
                    {
                        $inserted_id = 0;

                        $edit_record_id = $request->hid_record_id;
                        $lastest_id = $request->lastest_id;

                        if($edit_record_id > 0) {
                            $inserted_id = $edit_record_id;
                        }else{
                            $inserted_id = $lastest_id + 1;
                        }

                        $result = StorageHelper::store($path = 'public/what_we_sell/detail/'.$inserted_id.'/key_contact', array($key_contact_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $key_contact_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $key_contact_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$key_contact_image_path,
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
