<?php

namespace App\Modules\WhatWeSells\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\WhatWeSells\Http\Requests\StoreWhatWeSellsRequest;
use App\Modules\WhatWeSells\Http\Requests\UpdateWhatWeSellsRequest;
use App\Modules\WhatWeSells\Http\Repositories\WhatWeSellsRepository;
use App\Modules\WhatWeSells\Models\WhatWeSells;
use App\Modules\WhatWeSells\Models\WhatWeSellHighlight;
use App\Modules\Category\Models\Category;
use App\Modules\OurTeam\Models\OurTeam;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class WhatWeSellsController extends Controller
{
    protected $whatWeSellRepository;
    public function __construct(WhatWeSellsRepository $whatWeSellRepository){
        $this->whatWeSellRepository = $whatWeSellRepository;
    }


    public function showList()
    {
        return view('what_we_sells::showlist');
    }

    /**
     * Displays the what_we_sell index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $what_we_sells = $this->whatWeSellRepository->all([], false);

        return view('what_we_sells::index', [
            'what_we_sell' => app(WhatWeSells::class),
            'what_we_sells' => $what_we_sells
        ]);
    }

    public function whatWeSellReordering(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['whatwesell_id'] as $key => $what_we_sell_id) {
                $sequence_number = $key + 1;

                $this->whatWeSellRepository->update($what_we_sell_id, ['order'=>$sequence_number], true);
            }
            DB::commit();

            flash()->success(__('What We Sell are reordered Successfully!'));
            return redirect()->route('what_we_sell.what_we_sells.index');

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'What We Sell are reordered Failed!']));
            return redirect()->route('what_we_sell.what_we_sells.index')->with('fail', 'What We Sell are reordered Failed!');
        }
    }

    /**
     * Displays the create new what_we_sell view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::where('parent_id',null)->orderBy('name')->pluck('name','id')->all();
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $our_teams = [];
        foreach ($ourteam as $key => $value) {
            $our_teams[$value->id] = $value->name.'('.$value->position.')';
        }

        return view('what_we_sells::create', [
            'what_we_sell' => app(WhatWeSells::class),
            'what_we_sell_highlights' => [],
            'categories' => $categories,
            'our_teams' => $our_teams,
        ]);
    }

    /**
     * @param CreateWhatWeSells $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreWhatWeSellsRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->whatWeSellRepository->packData($request);
            // dd($payload);
            $what_we_sell = $this->whatWeSellRepository->create($payload);

            if ($what_we_sell) {

                $this->whatWeSellRepository->update($what_we_sell->id, ['order'=>$what_we_sell->id], true);

                if (isset($request->main_image)) {
                    $main_image = $request->main_image;
                    $file_path = Storage::put('what_we_sell/'.$what_we_sell->id, $main_image);
                    $file_name = $main_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->whatWeSellRepository->update($what_we_sell->id, $image_data, true);
                }
                if (isset($request->detail_banner_image)) {
                    $detail_banner_image = $request->detail_banner_image;
                    $detail_banner_file_path = Storage::put('what_we_sell/'.$what_we_sell->id, $detail_banner_image);
                    $detail_banner_file_name = $detail_banner_image->getClientOriginalName();
                    $detail_banner_full_path = Storage::url($detail_banner_file_path);

                    $image_data['detail_banner_file_name'] = $detail_banner_file_name;
                    $image_data['detail_banner_file_path'] = $detail_banner_file_path;
                    $image_data['detail_banner_full_path'] = $detail_banner_full_path;

                    $this->whatWeSellRepository->update($what_we_sell->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $what_we_sell->title]));
                return redirect(route('what_we_sell.what_we_sells.show', ['what_we_sell' => $what_we_sell ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'What We Sell Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the what_we_sell
     *
     * @param WhatWeSells $what_we_sell
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(WhatWeSells $what_we_sell)
    {
        $what_we_sell = $this->whatWeSellRepository->show('id', $what_we_sell->id, [], false);
        $what_we_sell_highlights = [];
        $categories = Category::where('parent_id',null)->orderBy('name')->pluck('name','id')->all();
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $our_teams = [];
        foreach ($ourteam as $key => $value) {
            $our_teams[$value->id] = $value->name.'('.$value->position.')';
        }

        return view('what_we_sells::show', [
            'what_we_sell' => $what_we_sell,
            'what_we_sell_highlights' => $what_we_sell_highlights,
            'categories' => $categories,
            'our_teams' => $our_teams,
        ]);
    }


    public function highLightList(WhatWeSells $what_we_sell)
    {
        $what_we_sell_highlights = WhatWeSellHighlight::where('what_we_sell_id',$what_we_sell->id)->orderBy('order')->get();

        return view('what_we_sells::highlight_list', [
            'what_we_sell' => $what_we_sell,
            'highlights' => $what_we_sell_highlights,
        ]);
    }

    public function highlightReordering(WhatWeSells $what_we_sell, Request $request)
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['highlight_id'] as $key => $highlight_id) {
                $sequence_number = $key + 1;

                WhatWeSellHighlight::where('id',$highlight_id)->update(['order'=>$sequence_number]);
            }
            DB::commit();

            flash()->success(__('What We Sell Highlight are reordered Successfully!'));
            return redirect()->route('what_we_sell.what_we_sells.highlight_list', $what_we_sell);

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'What We Sell Highlight are reordered Failed!']));
            return redirect()->route('what_we_sell.what_we_sells.highlight_list', $what_we_sell)->with('fail', 'What We Sell Highlight are reordered Failed!');
        }
    }

    public function highLightCreate(WhatWeSells $what_we_sell)
    {
        $highlight = app(WhatWeSellHighlight::class);

        return view('what_we_sells::highlight_create', [
            'what_we_sell' => $what_we_sell,
            'highlight' => $highlight,
        ]);
    }

    public function highLightStore(WhatWeSells $what_we_sell, Request $request)
    {
        DB::beginTransaction();
        try {

            $payload['what_we_sell_id'] = $what_we_sell->id;
            $payload['title'] = $request->title;
            $payload['description'] = $request->description;
            // dd($payload);

            $highlight = WhatWeSellHighlight::create($payload);

            if ($highlight) {

                WhatWeSellHighlight::where('id',$highlight->id)->update(['order'=>$highlight->id]);

                if (isset($request->highlight_image)) {
                    $highlight_image = $request->highlight_image;
                    $file_path = Storage::put('what_we_sell/'.$what_we_sell->id.'/highlight/'.$highlight->id, $highlight_image);
                    $file_name = $highlight_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    WhatWeSellHighlight::where('id',$highlight->id)->update($image_data);
                }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $highlight->title]));
                return redirect(route('what_we_sell.what_we_sells.highlight_list', ['what_we_sell' => $what_we_sell ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'What We Sell Highlight Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function highLightEdit($what_we_sell_id, $highlight_id)
    {
        $what_we_sell = WhatWeSells::find($what_we_sell_id);
        $highlight = WhatWeSellHighlight::find($highlight_id);

        return view('what_we_sells::highlight_edit', [
            'what_we_sell' => $what_we_sell,
            'highlight' => $highlight,
        ]);
    }

    public function highLightUpdate($what_we_sell_id, $highlight_id, Request $request)
    {
        DB::beginTransaction();
        try {

            $payload['what_we_sell_id'] = $what_we_sell_id;
            $payload['title'] = $request->title;
            $payload['description'] = $request->description;

            if (isset($request->highlight_image)) {
                $highlight_image = $request->highlight_image;
                $file_path = Storage::put('what_we_sell/'.$what_we_sell_id.'/highlight/'.$highlight_id, $highlight_image);
                $file_name = $highlight_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }
            // dd($payload);

            $updated = WhatWeSellHighlight::where('id',$highlight_id)->update($payload);

            DB::commit();

            $what_we_sell = WhatWeSells::find($what_we_sell_id);
            $highlight = WhatWeSellHighlight::find($highlight_id);
            flash()->success(__(':name has been created', ['name' => $highlight->title]));
            return redirect(route('what_we_sell.what_we_sells.highlight_list', ['what_we_sell' => $what_we_sell ]));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * @param WhatWeSells $what_we_sell
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(WhatWeSells $what_we_sell)
    {
        $what_we_sell = $this->whatWeSellRepository->show('id', $what_we_sell->id, [], false);
        $what_we_sell_highlights = [];
        $categories = Category::where('parent_id',null)->orderBy('name')->pluck('name','id')->all();
        $ourteam = OurTeam::all(['id', 'name', 'position']);
        $our_teams = [];
        foreach ($ourteam as $key => $value) {
            $our_teams[$value->id] = $value->name.'('.$value->position.')';
        }

        return view('what_we_sells::edit', [
            'what_we_sell' => $what_we_sell,
            'what_we_sell_highlights' => $what_we_sell_highlights,
            'categories' => $categories,
            'our_teams' => $our_teams,
        ]);
    }

    /**
     * Saves updates to an existing what_we_sell
     *
     * @param WhatWeSell       $what_we_sell
     * @param UpdateWhatWeSell $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateWhatWeSellsRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->whatWeSellRepository->packData($request);
            // dd($payload);
            
            if (isset($request->main_image)) {
                $main_image = $request->main_image;
                $file_path = Storage::put('what_we_sell/'.$id, $main_image);
                $file_name = $main_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }
            if (isset($request->detail_banner_image)) {
                $detail_banner_image = $request->detail_banner_image;
                $detail_banner_file_path = Storage::put('what_we_sell/'.$id, $detail_banner_image);
                $detail_banner_file_name = $detail_banner_image->getClientOriginalName();
                $detail_banner_full_path = Storage::url($detail_banner_file_path);

                $payload['detail_banner_file_name'] = $detail_banner_file_name;
                $payload['detail_banner_file_path'] = $detail_banner_file_path;
                $payload['detail_banner_full_path'] = $detail_banner_full_path;
            }

            $updated = $this->whatWeSellRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $what_we_sell = WhatWeSells::find($id);
                flash()->success(__(':name has been updated', ['name' => $what_we_sell->title]));
                return redirect(route('what_we_sell.what_we_sells.show', ['what_we_sell' => $what_we_sell ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'What We Sell Update Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a what_we_sell
     *
     * @param WhatWeSells $what_we_sell
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(WhatWeSells $what_we_sell)
    {
        try {
            $title = $what_we_sell->title;
            $what_we_sell->delete();

            return response()->json([ 'status'=>'success', 'message' => $title.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
