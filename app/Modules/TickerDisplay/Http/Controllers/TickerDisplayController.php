<?php

namespace App\Modules\TickerDisplay\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\TickerDisplay\Http\Requests\StoreTickerDisplayRequest;
use App\Modules\TickerDisplay\Http\Requests\UpdateTickerDisplayRequest;
use App\Modules\TickerDisplay\Http\Repositories\TickerDisplayRepository;
use App\Modules\TickerDisplay\Models\TickerDisplay;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class TickerDisplayController extends Controller
{
    protected $tickerDisplayRepository;
    public function __construct(TickerDisplayRepository $tickerDisplayRepository){
        $this->tickerDisplayRepository = $tickerDisplayRepository;
    }

    /**
     * Displays the ticker_display index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $ticker_displays = $this->tickerDisplayRepository->all([], false, 10);

        return view('ticker_display::index', [
            'ticker_display' => app(TickerDisplay::class),
            'ticker_displays' => $ticker_displays
        ]);
    }

    public function tickerDisplayReordering(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['tickerdisplay_id'] as $key => $ticker_display_id) {
                $sequence_number = $key + 1;

                $this->tickerDisplayRepository->update($ticker_display_id, ['order'=>$sequence_number], true);
            }
            DB::commit();

            flash()->success(__('Ticker Display are reordered Successfully!'));
            return redirect()->route('ticker_display.ticker_displays.index');

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Ticker Display are reordered Failed!']));
            return redirect()->route('ticker_display.ticker_displays.index')->with('fail', 'Ticker Display are reordered Failed!');
        }
    }

    /**
     * Displays the create new ticker_display view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('ticker_display::create', [
            'ticker_display' => app(TickerDisplay::class),
        ]);
    }

    /**
     * @param CreateTickerDisplay $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreTickerDisplayRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->tickerDisplayRepository->packData($request);
            // dd($payload);
            $ticker_display = $this->tickerDisplayRepository->create($payload);

            if ($ticker_display) {

                $this->tickerDisplayRepository->update($ticker_display->id, ['order'=>$ticker_display->id], true);

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $ticker_display->title]));
                return redirect(route('ticker_display.ticker_displays.show', ['ticker_display' => $ticker_display ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Ticker Display Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the ticker_display
     *
     * @param TickerDisplay $ticker_display
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TickerDisplay $ticker_display)
    {
        $ticker_display = $this->tickerDisplayRepository->show('id', $ticker_display->id, [], false);

        return view('ticker_display::show', [
            'ticker_display' => $ticker_display,
        ]);
    }

    /**
     * @param TickerDisplay $ticker_display
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TickerDisplay $ticker_display)
    {
        $ticker_displays = $this->tickerDisplayRepository->show('id', $ticker_display->id, [], false);

        return view('ticker_display::edit', [
            'ticker_display' => $ticker_displays,
        ]);
    }

    /**
     * Saves updates to an existing ticker_display
     *
     * @param TickerDisplay       $ticker_display
     * @param UpdateTickerDisplay $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateTickerDisplayRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->tickerDisplayRepository->packData($request);

            $updated = $this->tickerDisplayRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $ticker_display = TickerDisplay::find($id);
                flash()->success(__(':name has been updated', ['name' => $ticker_display->title]));
                return redirect(route('ticker_display.ticker_displays.show', ['ticker_display' => $ticker_display ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Ticker Display Update Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a ticker_display
     *
     * @param TickerDisplay $ticker_display
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(TickerDisplay $ticker_display)
    {
        try {
            $title = $ticker_display->title;
            $ticker_display->delete();

            return response()->json([ 'status'=>'success', 'message' => $title.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
