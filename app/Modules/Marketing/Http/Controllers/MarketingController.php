<?php

namespace App\Modules\Marketing\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Marketing\Http\Requests\StoreMarketingRequest;
use App\Modules\Marketing\Http\Requests\UpdateMarketingRequest;
use App\Modules\Marketing\Http\Repositories\MarketingRepository;
use App\Modules\Marketing\Models\Marketing;
use Carbon\Carbon;
use DB;

class MarketingController extends Controller
{
    protected $marketingRepository;
    public function __construct(MarketingRepository $marketingRepository){
        $this->marketingRepository = $marketingRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $marketings = $this->marketingRepository->all([], false, 100);

        $data = [
            'marketings' => [],
        ];
        return view('marketing::index',$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $marketing = app(Marketing::class);
        $countries = DB::table('countries')->pluck('name','id');
        $countries_codes = DB::table('countries')->pluck('country_code','id');//fake fake codes* need to  corrected
        $currencies = DB::table('countries')->pluck('currency_code','id');//fake fake currencies* need to  corrected
        $types = [
            'online'=>'Online',
            'in_room'=>'In-room'
        ];
        $data = [
            'types' => $types,
            'marketing' => $marketing,
            'countries' => $countries,
            'countries_codes' => $countries_codes,
            'currencies' => $currencies,
        ];
        return view('marketing::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarketingRequest $request)
    {
        try {
            $marketing = Marketing::create($this->packData($request));
            flash()->success(__(':name has been created', ['name' => $marketing->getTitle()]));
            return redirect(route('marketing.marketings.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the marketing
     *
     * @param Item $marketing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Marketing $marketing)
    {
        return view('marketing::show', [
            'marketing' => $marketing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Marketing $marketing)
    {
        $marketing = $this->marketingRepository->show('id', $marketing->id, [], true);
        // $marketing->timed_start = $this->formatDateForShow($marketing->timed_start);
        // $marketing->timed_first_lot_ends = $this->formatDateForShow($marketing->timed_first_lot_ends);
        $marketing->timed_start = date('Y-m-d h:i:s');
        $marketing->timed_first_lot_ends = date('Y-m-d h:i:s');
        // dd($marketing);

        $countries = DB::table('countries')->pluck('name','id');
        $countries_codes = DB::table('countries')->pluck('country_code','id');//fake fake codes* need to  corrected
        $currencies = DB::table('countries')->pluck('currency_code','id');//fake fake currencies* need to  corrected
        $types = [
            'online'=>'Online',
            'in_room'=>'In-room'
        ];
        $data = [
            'types' => $types,
            'marketing' => $marketing,
            'countries' => $countries,
            'countries_codes' => $countries_codes,
            'currencies' => $currencies,
        ];
        return view('marketing::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarketingRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update marketing
            $this->marketingRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => Marketing::find($id)->title]));
            return redirect()->route('marketing.marketings.index')->with('success', 'Marketing Updated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
            // return redirect()->route('marketing.marketings.index')->with('fail', 'Marketing Updating Failed!');
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
            // check marketing can destroy or not
            // $canDestroy = $this->marketingRepository->canDestroy($id);

            // if ($canDestroy) {
                $this->marketingRepository->destroy($id);
                DB::commit();

                return redirect()->route('marketing.marketings.index')->with('success', 'Marketing Deactivated Successfully!');
            // } else {
            //     return redirect()->route('marketing.marketings.index')->with('fail', 'Cannot deactivate as this marketing is associated with childrens!');
            // }
                /* disable canDestory method . Need to update*/
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('marketing.marketings.index')->with('fail', 'Marketing Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->marketingRepository->restore($id);
            DB::commit();

            return redirect()->route('marketing.marketings.index')->with('success', 'Marketing Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('marketing.marketings.index')->with('fail', 'Marketing Activating Failed!');
        }
    }
    
    protected function packData($request)
    {
        $payload['legeacy_id'] = 0;
        $payload['marketing_created_date_time_utc'] = '';
        $payload['time_is_already_utc'] = false;
        return $payload;
    }
}