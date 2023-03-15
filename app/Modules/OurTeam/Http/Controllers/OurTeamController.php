<?php

namespace App\Modules\OurTeam\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\OurTeam\Http\Requests\StoreOurTeamRequest;
use App\Modules\OurTeam\Http\Requests\UpdateOurTeamRequest;

use App\Modules\OurTeam\Models\OurTeam;
use App\Modules\OurTeam\Models\OurTeamInfo;

use App\Modules\OurTeam\Http\Repositories\OurTeamRepository;
use App\Modules\OurTeam\Http\Repositories\OurTeamInfoRepository;
use Illuminate\Support\Facades\Storage;

class OurTeamController extends BaseController
{
    protected $ourTeamRepository;
    protected $ourTeamInfoRepository;
    public function __construct(OurTeamRepository $ourTeamRepository, OurTeamInfoRepository $ourTeamInfoRepository){
        $this->ourTeamRepository = $ourTeamRepository;
        $this->ourTeamInfoRepository = $ourTeamInfoRepository;
    }

    public function showlist()
    {
        return view('our_team::showlist');
    }

    public function index()
    {
        $our_teams = $this->ourTeamRepository->all([], false, 100);

        $data = [
            'our_teams' => $our_teams,
        ];

        return view('our_team::index',$data);
    }

    public function teamMemberReordering(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['ourteam_id'] as $key => $our_team_id) {
                $sequence_number = $key + 1;

                $this->ourTeamRepository->update($our_team_id, ['order'=>$sequence_number], true);
            }
            DB::commit();

            flash()->success(__('Team Members are reordered Successfully!'));
            return redirect()->route('our_team.our_teams.index');

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Team Members are reordered Failed!']));
            return redirect()->route('our_team.our_teams.index')->with('fail', 'Team Members are reordered Failed!');
        }
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner = '';
        $banner2 = '';
        $hid_record_id = 0;
        $lastest_id = 0;
        $our_team = app(OurTeam::class);
        $lastest_data = DB::table('our_team')->latest('id')->first();
        if($lastest_data)
        {
            $lastest_id = $lastest_data->id;
        }

        $data = [
            'our_team' => $our_team,
            'hide_team_image_ids' => '',
            'hide_team_full_path_ids' => '',
            'hide_team_image2_ids' => '',
            'hide_team_full_path2_ids' => '',
            'banner' => $banner,
            'banner2' => $banner2,
            'hid_record_id' => $hid_record_id,
            'lastest_id' => $lastest_id
        ];
        return view('our_team::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function storeOld(StoreOurTeamRequest $request)
    // {
    //     try {

    //         $our_team = OurTeam::create($this->packData($request));
    //         flash()->success(__(':name has been created', ['name' => $our_team->name]));
    //         return redirect(route('our_team.our_teams.index'));

    //     } catch (\Exception $e) {
    //         flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

    //         return redirect()->back()->withInput();
    //     }
    // }
    public function store(StoreOurTeamRequest $request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $payload = $this->ourTeamRepository->packData($request);
            // dd($payload);
            $our_team = $this->ourTeamRepository->create($payload);

            if ($our_team) {

                $this->ourTeamRepository->update($our_team->id, ['order'=>$our_team->id], true);

                if (isset($request->profile_image) && $request->profile_image != null) {
                    $profile_image = $request->profile_image;
                    $file_path = Storage::put('our_team/'.$our_team->id, $profile_image);
                    $file_name = $profile_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['profile_image'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->ourTeamRepository->update($our_team->id, $image_data, true);
                }
                if (isset($request->profile_image2) && $request->profile_image2 != null) {
                    $profile_image2 = $request->profile_image2;
                    $profile_image2_file_path = Storage::put('our_team/'.$our_team->id, $profile_image2);
                    $profile_image2_file_name = $profile_image2->getClientOriginalName();
                    $profile_image2_full_path = Storage::url($profile_image2_file_path);

                    $image_data['profile_image2'] = $profile_image2_file_name;
                    $image_data['file_path2'] = $profile_image2_file_path;
                    $image_data['full_path2'] = $profile_image2_full_path;

                    $this->ourTeamRepository->update($our_team->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $our_team->name]));
                return redirect(route('our_team.our_teams.show', ['our_team' => $our_team ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Team Member Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
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
    public function show(OurTeam $OurTeam)
    {
        return view('our_team::show', [
            'our_team' => $OurTeam
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OurTeam $OurTeam)
    {
        $banner = '';
        $hid_record_id = 0;
        $our_team = $this->ourTeamRepository->show('id', $OurTeam->id, [], true);
        $lastest_id = DB::table('our_team')->latest('id')->first();

        $data = [
            'our_team' => $our_team,
            'hide_team_image_ids' => $our_team->profile_image,
            'hide_team_full_path_ids' => $our_team->full_path,
            'banner' => $our_team->full_path,
            'hid_record_id' => $our_team->id,
            'lastest_id' => $lastest_id->id,
            'banner2' => $our_team->full_path2,
            'hide_team_image2_ids' => $our_team->profile_image2,
            'hide_team_full_path2_ids' => $our_team->full_path2
        ];
        return view('our_team::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateOld(UpdateOurTeamRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update team
            $this->ourTeamRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => OurTeam::find($id)->name]));
            return redirect()->route('our_team.our_teams.index')->with('success', 'Our Team Updated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }
    public function update($id, UpdateOurTeamRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->ourTeamRepository->packData($request);
            // dd($payload);
            
            if (isset($request->profile_image) && $request->profile_image != null) {
                $profile_image = $request->profile_image;
                $file_path = Storage::put('our_team/'.$id, $profile_image);
                $file_name = $profile_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }
            if (isset($request->profile_image2) && $request->profile_image2 != null) {
                $profile_image2 = $request->profile_image2;
                $profile_image2_file_path = Storage::put('our_team/'.$id, $profile_image2);
                $profile_image2_file_name = $profile_image2->getClientOriginalName();
                $profile_image2_full_path = Storage::url($profile_image2_file_path);

                $payload['profile_image2'] = $profile_image2_file_name;
                $payload['file_path2'] = $profile_image2_file_path;
                $payload['full_path2'] = $profile_image2_full_path;
            }

            $updated = $this->ourTeamRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $our_team = OurTeam::find($id);
                flash()->success(__(':name has been updated', ['name' => $our_team->name]));
                return redirect(route('our_team.our_teams.show', ['our_team' => $our_team ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Team Member Update Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OurTeam $our_team)
    {
        // dd($our_team);
        try {
            $name = $our_team->name;
            $our_team->forceDelete();

            return response()->json([ 'status'=>'success', 'message' => $name.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
    public function destroyOld($id)
    {
        DB::beginTransaction();

        try {
                $this->ourTeamRepository->destroy($id);
                DB::commit();

                return redirect()->route('our_team.our_teams.index')->with('success', 'Our Team Deactivated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('our_team.our_teams.index')->with('fail', 'Our Team Deactivating Failed!');
        }
    }

    public function imageUpload(Request $request)
    {
        try{
            if ($request->file('our_team_image') || $request->file('team_image2')) {
                $our_team_image = '';
                $store_folder_name = '';

                if($request->file('our_team_image'))
                {
                    $our_team_image = $request->file('our_team_image');
                    $store_folder_name = 'image1';
                }
                if($request->file('team_image2'))
                {
                    $our_team_image = $request->file('team_image2');
                    $store_folder_name = 'image2';
                }

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($our_team_image))
                    {
                        $inserted_id = 0;

                        $edit_record_id = $request->hid_record_id;
                        $lastest_id = $request->lastest_id;

                        if($edit_record_id > 0) {
                            $inserted_id = $edit_record_id;
                        }else{
                            $inserted_id = $lastest_id + 1;
                        }

                        $result = StorageHelper::store($path = 'public/our_team/'.$inserted_id.'/'.$store_folder_name, array($our_team_image), $wipeExisting=true);

                        // $saved_result = StorageHelper::add($path = 'public/our_team', array($our_team_image), $wipeExisting=true);

                        // $result = StorageHelper::get($path = 'public/our_team');

                        $filename = $result[0]['name'];
                        $our_team_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $our_team_image_path,
                        ];

                        // $result = $storageHelper->add($path = 'test/file', $files = array($request->file));
                        // dd($storageHelper->get($path = 'test/file'));
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$filename,
                    'saved_storage_filepath'=>$our_team_image_path,
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
        $our_team_data = OurTeamInfo::all();
        $our_team = [];
        $banner = '';

        if (!$our_team_data->isEmpty()){
            $our_team = $our_team_data->first();
            $banner = $our_team->banner_image;
        }

        $data = [
                'our_team_data' => $our_team_data,
                'our_team' => $our_team,
                'banner' => $banner
            ];

        return view('our_team::info_index',$data);
    }

    public function editcontent()
    {
        $our_team_data = OurTeamInfo::all();
        $our_team = [];
        $banner = '';

        if (!$our_team_data->isEmpty()){
            $our_team = $our_team_data->first();
            $banner = $our_team->banner_image;
        }

        $data = [
                'our_team_data' => $our_team_data,
                'our_team' => $our_team,
                'hide_team_info_image_ids' => $banner,
                'banner' => $banner
            ];

        return view('our_team::edit_info_index',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($team_banner_image = $request->file('team_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';
                $filepath = '';

                    if(isset($team_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/our_team_info', array($team_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $filepath = $result[0]['data'];
                        $team_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $team_banner_image_path,
                        ];
                    }
                }

                $data = [
                    'status'=>1,
                    'saved_filepath'=>$team_banner_image_path,
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
        $our_team_data = OurTeamInfo::all();

        $action = '';
        if (!$our_team_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packInfoData($request);

            if($action == 'save') {
                $result = OurTeamInfo::create($payload);
            }else{
                $this->ourTeamInfoRepository->update(1, $payload, true);
            }
            DB::commit();

           flash()->success(__('Update Our Team Main Successfully'));
            return redirect(route('our_team.our_teams.infoIndex'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packInfoData($request)
    {
        $payload['banner_image'] = $request->hide_team_info_image_ids;
        $payload['caption'] = $request->caption;

        $payload['title_header'] = $request->title_header;
        $payload['title_blog'] = $request->title_blog;

        return $payload;
    }
}
