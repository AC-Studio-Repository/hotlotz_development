<?php

namespace App\Modules\InternalAdvert\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\InternalAdvert\Models\InternalAdvert;
use DB;
use Illuminate\Http\Request;


class InternalAdvertController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $internalAds = InternalAdvert::all();
        $data = [
            'internal_adverts' => $internalAds
        ];
        return view('internal_advert::index', $data);
    }

    public function create(){
        $internalAdvert = app(InternalAdvert::class);
        $data = [
            'internal_advert' => $internalAdvert
        ];
        return view('internal_advert::create',$data);
    }

    public function detail($id)
    {
        $internalAdvert = InternalAdvert::where('id',$id)->first();
        $data = [
            'internal_advert' => $internalAdvert
        ];
        return view("internal_advert::detail", $data);
    }

    public function edit($id)
    {
        $internalAdvert = InternalAdvert::find($id);
        $data = [
            'internal_advert' => $internalAdvert
        ];
        return view("internal_advert::edit", $data);
    }

    public function store(Request $request)
    {
        $internalAdvert = new InternalAdvert;

        if (isset($request->name)) {
            $internalAdvert->name = $request->name;
        }
        if (isset($request->description)) {
            $internalAdvert->description = $request->description;
        }
        if (isset($request->url)) {
            $internalAdvert->url = $request->url;
        }

        try {
            $internalAdvert->save();
            flash()->success(__('Internal Advert created successfully'));
            return redirect()->route('internal_advert.internal_advert.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->fail(__('Error. Cannot create Internal Advert.'));
            return redirect()->route('internal_advert.internal_advert.index');
        }
    }

    public function update($id, Request $request)
    {
        $internalAdvert = InternalAdvert::find($id);

        if (isset($request->name)) {
            $internalAdvert->name = $request->name;
        }
        if (isset($request->description)) {
            $internalAdvert->description = $request->description;
        }
        if (isset($request->url)) {
            $internalAdvert->url = $request->url;
        }

        try {
            $internalAdvert->save();
            flash()->success(__('Internal Advert updated successfully'));
            return redirect()->route('internal_advert.internal_advert.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->fail(__('Error. Cannot update Internal Advert.'));
            return redirect()->route('internal_advert.internal_advert.index');
        }
    }

    public function destroy(InternalAdvert $internalAdvert)
    {
        DB::beginTransaction();
        try {
            InternalAdvert::destroy($internalAdvert->id);
            DB::commit();
            flash()->success(__('Internal Advert deleted successfully'));
            return redirect()->route('internal_advert.internal_advert.index')->with('success', 'Internal Advert deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->fail(__('Error. Cannot delete Internal Advert.'));
            return redirect()->route('internal_advert.internal_advert.index');
        }
    }
}
