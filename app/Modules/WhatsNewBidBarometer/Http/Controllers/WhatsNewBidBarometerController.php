<?php

namespace App\Modules\WhatsNewBidBarometer\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\WhatsNewBidBarometer\Http\Requests\StoreWhatsNewBidBarometerRequest;
use App\Modules\WhatsNewBidBarometer\Http\Requests\UpdateWhatsNewBidBarometerRequest;
use App\Modules\WhatsNewBidBarometer\Http\Repositories\WhatsNewBidBarometerRepository;
use App\Modules\WhatsNewBidBarometer\Models\WhatsNewBidBarometer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class WhatsNewBidBarometerController extends Controller
{
    protected $whatsNewBidBarometerRepository;
    public function __construct(WhatsNewBidBarometerRepository $whatsNewBidBarometerRepository){
        $this->whatsNewBidBarometerRepository = $whatsNewBidBarometerRepository;
    }

    /**
     * Displays the whats_new_bid_barometer index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $welcome = WhatsNewBidBarometer::first();
        $whats_new_bid_barometer = app(WhatsNewBidBarometer::class);
        $id = 0;
        if($welcome != null){
            $whats_new_bid_barometer = $welcome;
            $id = $welcome->id;
        }
        // dd($whats_new_bid_barometer);

        return view('whats_new_bid_barometer::index', [
            'whats_new_bid_barometer' => $whats_new_bid_barometer,
            'id' => $id
        ]);
    }

    /**
     * Displays the create new whats_new_bid_barometer view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('whats_new_bid_barometer::create', [
            'whats_new_bid_barometer' => app(WhatsNewBidBarometer::class),
        ]);
    }

    /**
     * @param CreateWhatsNewBidBarometer $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreWhatsNewBidBarometerRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {

            $payload = $this->whatsNewBidBarometerRepository->packData($request);
            // dd($payload);

            $whats_new_bid_barometer = $this->whatsNewBidBarometerRepository->create($payload);

            if ($whats_new_bid_barometer) {
                if (isset($request->bid_barometer_image)) {
                    $bid_barometer_image = $request->bid_barometer_image;
                    $file_path = Storage::put('whats_new_bid_barometer/'.$whats_new_bid_barometer->id, $bid_barometer_image);
                    $file_name = $bid_barometer_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->whatsNewBidBarometerRepository->update($whats_new_bid_barometer->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':title has been created', ['title' => $whats_new_bid_barometer->title]));
                return redirect(route('whats_new_bid_barometer.whats_new_bid_barometers.show', ['whats_new_bid_barometer' => $whats_new_bid_barometer ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => "What's New Bid Barometer Create Failed!"]));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the whats_new_bid_barometer
     *
     * @param WhatsNewBidBarometer $whats_new_bid_barometer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(WhatsNewBidBarometer $whats_new_bid_barometer)
    {
        return view('whats_new_bid_barometer::show', [
            'whats_new_bid_barometer' => $whats_new_bid_barometer,
        ]);
    }

    /**
     * @param WhatsNewBidBarometer $whats_new_bid_barometer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(WhatsNewBidBarometer $whats_new_bid_barometer)
    {
        $whats_new_bid_barometers = $this->whatsNewBidBarometerRepository->show('id', $whats_new_bid_barometer->id, [], false);

        return view('whats_new_bid_barometer::edit', [
            'whats_new_bid_barometer' => $whats_new_bid_barometers,
        ]);
    }

    /**
     * Saves updates to an existing whats_new_bid_barometer
     *
     * @param WhatsNewBidBarometer       $whats_new_bid_barometer
     * @param UpdateWhatsNewBidBarometer $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateWhatsNewBidBarometerRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->whatsNewBidBarometerRepository->packData($request);
            if (isset($request->bid_barometer_image)) {
                $bid_barometer_image = $request->bid_barometer_image;
                $file_path = Storage::put('whats_new_bid_barometer/'.$id, $bid_barometer_image);
                $file_name = $bid_barometer_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            $updated = $this->whatsNewBidBarometerRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $whats_new_bid_barometer = WhatsNewBidBarometer::find($id);
                flash()->success(__(':name has been updated', ['name' => $whats_new_bid_barometer->title]));
                return redirect(route('whats_new_bid_barometer.whats_new_bid_barometers.show', ['whats_new_bid_barometer' => $whats_new_bid_barometer ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => "What's New Bid Barometer Update Failed!"]));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a whats_new_bid_barometer
     *
     * @param WhatsNewBidBarometer $whats_new_bid_barometer
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(WhatsNewBidBarometer $whats_new_bid_barometer)
    {
        try {
            $title = $whats_new_bid_barometer->title;
            $whats_new_bid_barometer->delete();

            return response()->json([ 'status'=>'success', 'message' => $title.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
