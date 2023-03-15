<?php

namespace App\Modules\SysConfig\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\SysConfig\Http\Requests\StoreSysConfigRequest;
use App\Modules\SysConfig\Http\Requests\UpdateSysConfigRequest;
use App\Modules\SysConfig\Http\Repositories\SysConfigRepository;
use App\Modules\SysConfig\Models\SysConfig;
use DB;
use App\Events\SysConfigActionEvent;

class SysConfigController extends Controller
{
    protected $sysConfigRepository;
    public function __construct(SysConfigRepository $sysConfigRepository){
        $this->sysConfigRepository = $sysConfigRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inputs = ['Monday'=>'monday','Tuesday'=>'tuesday','Wednesday'=>'wednesday','Thursday'=>'thursday','Friday'=>'friday','Saturday'=>'saturday','Sunday'=>'sunday']; 
        $sys_config = SysConfig::first();
        // dd($sys_config);

        $data = [
            'inputs' => $inputs,
            'sys_config' => $sys_config,
        ];
        return view('sys_config::index',$data);
    }

    public function save(Request $request)
    {
        try {
            // dd($request->all());
            // prepare variables
            $payload = $this->packData($request);

            $sys_config = SysConfig::first();

            if(isset($sys_config)){                
                // update
                $result = $this->sysConfigRepository->update($sys_config->id, $payload, true);
                $action = 'updated';
            }else{
                $result = $this->sysConfigRepository->create($payload);
                $action = 'created';
            }
            event(new SysConfigActionEvent($action));

            flash()->success(__('System Config has been saved'));
            return redirect()->route('sys_config.sys_configs.index')->with('success', 'System Config Saved Successfully!');

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
            // return redirect()->route('sys_config.sys_configs.index')->with('fail', 'System Config Saving Failed!');
        }
    }
    
    protected function packData($request)
    {
        $payload['monday_start_time'] = $request->monday_start_time;
        $payload['monday_end_time'] = $request->monday_end_time;
        $payload['is_closed_monday'] = isset($request->is_closed_monday)?'Y':'N';

        $payload['tuesday_start_time'] = $request->tuesday_start_time;
        $payload['tuesday_end_time'] = $request->tuesday_end_time;
        $payload['is_closed_tuesday'] = isset($request->is_closed_tuesday)?'Y':'N';

        $payload['wednesday_start_time'] = $request->wednesday_start_time;
        $payload['wednesday_end_time'] = $request->wednesday_end_time;
        $payload['is_closed_wednesday'] = isset($request->is_closed_wednesday)?'Y':'N';

        $payload['thursday_start_time'] = $request->thursday_start_time;
        $payload['thursday_end_time'] = $request->thursday_end_time;
        $payload['is_closed_thursday'] = isset($request->is_closed_thursday)?'Y':'N';

        $payload['friday_start_time'] = $request->friday_start_time;
        $payload['friday_end_time'] = $request->friday_end_time;
        $payload['is_closed_friday'] = isset($request->is_closed_friday)?'Y':'N';

        $payload['saturday_start_time'] = $request->saturday_start_time;
        $payload['saturday_end_time'] = $request->saturday_end_time;
        $payload['is_closed_saturday'] = isset($request->is_closed_saturday)?'Y':'N';

        $payload['sunday_start_time'] = $request->sunday_start_time;
        $payload['sunday_end_time'] = $request->sunday_end_time;
        $payload['is_closed_sunday'] = isset($request->is_closed_sunday)?'Y':'N';

        return $payload;
    }
}