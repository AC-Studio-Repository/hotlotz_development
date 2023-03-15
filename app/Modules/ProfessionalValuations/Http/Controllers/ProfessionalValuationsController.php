<?php

namespace App\Modules\ProfessionalValuations\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Models\GeneralInfo;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\ProfessionalValuations\Http\Requests\StoreProfessionalValuationsRequest;
use App\Modules\ProfessionalValuations\Http\Requests\UpdateProfessionalValuationsRequest;

use App\Modules\ProfessionalValuations\Models\ProfessionalValuations;
use App\Modules\ProfessionalValuations\Models\ProfessionalValuationsBlog;
use App\Modules\OurTeam\Models\OurTeam;

use App\Modules\ProfessionalValuations\Http\Repositories\ProfessionalValuationsRepository;
use App\Modules\ProfessionalValuations\Http\Repositories\ProfessionalValuationsBlogRepository;

class ProfessionalValuationsController extends BaseController
{
    protected $professionalValuationsRepository;
    protected $professionalValuationsBlogRepository;
    public function __construct(ProfessionalValuationsRepository $professionalValuationsRepository, ProfessionalValuationsBlogRepository $professionalValuationsBlogRepository){
        $this->professionalValuationsRepository = $professionalValuationsRepository;
        $this->professionalValuationsBlogRepository = $professionalValuationsBlogRepository;
    }

    public function index()
    {
        return view('professional_valuations::index');
    }

    public function contentIndex()
    {
        $professional_valuations_data = ProfessionalValuations::all();
        $professional_valuations = [];
        $banner = '';
        $blogs = ProfessionalValuationsBlog::all();
        $key_contact_1 = '';
        $key_contact_2 = '';

        if (!$professional_valuations_data->isEmpty()){
            $professional_valuations = $professional_valuations_data->first();
            $banner = $professional_valuations->banner_image;
            if($professional_valuations->key_contact_1 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $professional_valuations->key_contact_1)->first();
                $key_contact_1 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
            }
            if($professional_valuations->key_contact_2 != 0)
            {
                $key_contact_data = OurTeam::where('id', '=', $professional_valuations->key_contact_2)->first();
                $key_contact_2 = $key_contact_data->name .'( '. $key_contact_data->position .' )';
            }
        }

        $data = [
                'professional_valuations_data' => $professional_valuations_data,
                'professional_valuations' => $professional_valuations,
                'banner' => $banner,
                'blogs' => $blogs,
                'key_contact_1' => $key_contact_1,
                'key_contact_2' => $key_contact_2
            ];

        return view('professional_valuations::content_index',$data);
    }

    public function editcontent()
    {
        $professional_valuations_data = ProfessionalValuations::all();
        $professional_valuations = [];
        $banner = '';
        $blog_count = 0;
        $blogs = ProfessionalValuationsBlog::all();
        $latest_id = 0;
        $id_array = [];
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $key_contact_1 = 0;
        $key_contact_2 = 0;

        if (!$professional_valuations_data->isEmpty()){
            $professional_valuations = $professional_valuations_data->first();
            $banner = $professional_valuations->banner_image;
            $key_contact_1 = $professional_valuations->key_contact_1;
            $key_contact_2 = $professional_valuations->key_contact_2;
        }

        if(!$blogs->isEmpty())
        {
            $status = 'edit';
            $blog_count = $blogs->count();
            
            $latest_data = DB::table('professional_valuations_info_blogs')->orderBy('id', 'DESC')->first();
            $latest_id = $latest_data->id;
            foreach($blogs as $blog)
            {
                array_push($id_array,$blog->id);
            }
        }else{
            $status = 'create';
        }

        $data = [
                'professional_valuations_data' => $professional_valuations_data,
                'professional_valuations' => $professional_valuations,
                'hide_image_ids' => $banner,
                'banner' => $banner,
                'blogs' => $blogs,
                'latest_id' => $latest_id,
                'status' => $status,
                'id_array' => $id_array,
                'blog_count' => $blog_count,
                'ourteam' => $ourteam,
                'key_contact_1' => $key_contact_1,
                'key_contact_2' => $key_contact_2
            ];

        return view('professional_valuations::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($professional_valuations_banner_image = $request->file('professional_valuations_banner')) {

                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($professional_valuations_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/professional_valuations_info/banner', array($professional_valuations_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $professional_valuations_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $professional_valuations_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$professional_valuations_banner_image_path,
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
        $professional_valuations_data = ProfessionalValuations::all();

        $action = '';
        if (!$professional_valuations_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = ProfessionalValuations::create($payload);
            }else{
                $this->professionalValuationsRepository->update(1, $payload, true);
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
                        $professional_valuations_blog = ProfessionalValuationsBlog::create($payload);
                        DB::commit();
                    }
                    else if($hid_edit_id  > 0 && $hid_delete_id > 0)
                    {
                        //delete
                        $this->professionalValuationsBlogRepository->destroy($hid_delete_id);
                        DB::commit();
                        
                    }
                    else if($hid_edit_id  > 0)
                    {
                        //update
                        $this->professionalValuationsBlogRepository->update($hid_edit_id, $payload, true);
                        DB::commit();
                    }
                }
            }
           
           flash()->success(__('Update Professional Valuations Successfully'));
            return redirect(route('professional_valuation.professional_valuations.contentIndex')); 
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner_image'] = $request->hide_professional_valuations_image_ids;
        $payload['caption'] = $request->caption;

        $payload['title'] = $request->blog_header_1;
        $payload['blog'] = $request->title_blog;

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
}
