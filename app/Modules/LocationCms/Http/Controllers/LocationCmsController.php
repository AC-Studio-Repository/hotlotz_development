<?php

namespace App\Modules\LocationCms\Http\Controllers;

use App\Modules\SysConfig\Models\SysConfig;
use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;

use App\Modules\LocationCms\Models\LocationCms;

use App\Modules\LocationCms\Http\Repositories\LocationCmsRepository;

class LocationCmsController extends BaseController
{
    protected $locationCmsRepository;
    public function __construct(LocationCmsRepository $locationCmsRepository){
        $this->locationCmsRepository = $locationCmsRepository;
    }

    public function index()
    {
        $location_cms_data = LocationCms::all();
        $location_cms = [];
        $banner = '';

        $days = [
            [
                "label" => 'Monday',
                'name' => 'mon',
                'value' => SysConfig::getOpeningTime('monday')
            ],
            [
                "label" => 'Tuesday',
                'name' => 'tue',
                'value' => SysConfig::getOpeningTime('tuesday')
            ],
            [
                "label" => 'Wednesday',
                'name' => 'wed',
                'value' => SysConfig::getOpeningTime('wednesday')
            ],
            [
                "label" => 'Thursday',
                'name' => 'thur',
                'value' => SysConfig::getOpeningTime('thursday')
            ],
            [
                "label" => 'Friday',
                'name' => 'fri',
                'value' => SysConfig::getOpeningTime('friday')
            ],
            [
                "label" => 'Saturday',
                'name' => 'sat',
                'value' => SysConfig::getOpeningTime('saturday')
            ],
            [
                "label" => 'Sunday',
                'name' => 'sun',
                'value' => SysConfig::getOpeningTime('sunday')
            ]
        ];

        if (!$location_cms_data->isEmpty()){
            $location_cms = $location_cms_data->first();
            $banner = $location_cms->banner;
        }
        $days = collect($days);

        $data = [
                'location_cms_data' => $location_cms_data,
                'location_cms' => $location_cms,
                'banner' => $banner,
                'days' => $days
            ];

        return view('location_cms::index',$data);
    }

    public function editcontent()
    {
        $location_cms_data = LocationCms::all();
        $location_cms = [];
        $banner = '';

        $days = [
            [
                "label" => 'Monday',
                'name' => 'mon',
                'value' => SysConfig::getOpeningTime('monday')
            ],
            [
                "label" => 'Tuesday',
                'name' => 'tue',
                'value' => SysConfig::getOpeningTime('tuesday')
            ],
            [
                "label" => 'Wednesday',
                'name' => 'wed',
                'value' => SysConfig::getOpeningTime('wednesday')
            ],
            [
                "label" => 'Thursday',
                'name' => 'thur',
                'value' => SysConfig::getOpeningTime('thursday')
            ],
            [
                "label" => 'Friday',
                'name' => 'fri',
                'value' => SysConfig::getOpeningTime('friday')
            ],
            [
                "label" => 'Saturday',
                'name' => 'sat',
                'value' => SysConfig::getOpeningTime('saturday')
            ],
            [
                "label" => 'Sunday',
                'name' => 'sun',
                'value' => SysConfig::getOpeningTime('sunday')
            ]
        ];

        if (!$location_cms_data->isEmpty()){
            $location_cms = $location_cms_data->first();
            $banner = $location_cms->banner;
        }

        $days = collect($days);

        $data = [
                'location_cms_data' => $location_cms_data,
                'location_cms' => $location_cms,
                'hide_locationCms_image_ids' => $banner,
                'banner' => $banner,
                'days' => $days
            ];

        return view('location_cms::edit',$data);
    }

    public function banner_image_upload(Request $request)
    {
        try{
            if ($locationCms_banner_image = $request->file('locationCms_banner_image')) {
                $p1 = [];
                $p2 = [];
                $images_ids = [];
                $filename = '';

                    if(isset($locationCms_banner_image))
                    {
                        $result = StorageHelper::store($path = 'public/location_cms', array($locationCms_banner_image), $wipeExisting=true);

                        $filename = $result[0]['name'];
                        $locationCms_banner_image_path = $result[0]['data'];

                        $insert_item_imgs = [
                            'file_name' => $filename,
                            'file_path' => $locationCms_banner_image_path,
                        ];
                    }
                }
                $data = [
                    'status'=>1,
                    'saved_filepath'=>$locationCms_banner_image_path,
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

        $location_cms_data = LocationCms::all();

        $action = '';
        if (!$location_cms_data->isEmpty()){
            $action = 'update';
        }else{
            $action = 'save';
        }

        DB::beginTransaction();

        try {
            $payload = $this->packData($request);

            if($action == 'save') {
                $result = LocationCms::create($payload);
            }else{
                $this->locationCmsRepository->update(1, $payload, true);
            }
            DB::commit();

           flash()->success(__('Update Location Successfully'));
            return redirect(route('location_cms.location_cmss.index'));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    protected function packData($request)
    {
        $payload['banner'] = $request->hide_locationCms_image_ids;
        $payload['caption'] = $request->caption;
        $payload['title_header'] = $request->title_header;
        $payload['title_blog'] = $request->title_blog;
        $payload['direction_header'] = $request->direction_header;
        $payload['direction_blog'] = $request->direction_blog;
        $payload['saleroom_details'] = $request->saleroom_details;
        $payload['mon'] = $request->mon;
        $payload['tue'] = $request->tue;
        $payload['wed'] = $request->wed;
        $payload['thur'] = $request->thur;
        $payload['fri'] = $request->fri;
        $payload['sat'] = $request->sat;
        $payload['sun'] = $request->sun;

        return $payload;
    }

}
