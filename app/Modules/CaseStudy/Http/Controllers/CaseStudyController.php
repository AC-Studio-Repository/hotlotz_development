<?php

namespace App\Modules\CaseStudy\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CaseStudy\Models\CaseStudy;
use DB;
use Illuminate\Http\Request;


class CaseStudyController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $caseStudies = CaseStudy::all();
        $data = [
            'case_studies' => $caseStudies
        ];
        return view('case_study::index', $data);
    }

    public function create(){
        $caseStudy = app(CaseStudy::class);
        $data = [
            'case_study' => $caseStudy
        ];
        return view('case_study::create',$data);
    }

    public function detail($id)
    {
        $caseStudy = CaseStudy::where('id',$id)->first();
        $data = [
            'case_study' => $caseStudy
        ];
        return view("case_study::detail", $data);
    }

    public function edit($id)
    {
        $caseStudy = CaseStudy::find($id);
        $data = [
            'case_study' => $caseStudy
        ];
        return view("case_study::edit", $data);
    }

    public function store(Request $request)
    {
        $caseStudy = new CaseStudy;

        if (isset($request->name)) {
            $caseStudy->name = $request->name;
        }
        if (isset($request->abstract)) {
            $caseStudy->abstract = $request->abstract;
        }
        if (isset($request->link)) {
            $caseStudy->link = $request->link;
        }

        try {
            $caseStudy->save();
            flash()->success(__('Case Study created successfully'));
            return redirect()->route('case_study.case_study.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->fail(__('Error. Cannot create Case Study.'));
            return redirect()->route('case_study.case_study.index');
        }
    }

    public function update($id, Request $request)
    {
        $caseStudy = CaseStudy::find($id);

        if (isset($request->name)) {
            $caseStudy->name = $request->name;
        }
        if (isset($request->abstract)) {
            $caseStudy->abstract = $request->abstract;
        }
        if (isset($request->link)) {
            $caseStudy->link = $request->link;
        }

        try {
            $caseStudy->save();
            flash()->success(__('Case Study updated successfully'));
            return redirect()->route('case_study.case_study.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->fail(__('Error. Cannot update Case Study.'));
            return redirect()->route('case_study.case_study.index');
        }
    }

    public function destroy(CaseStudy $caseStudy)
    {
        DB::beginTransaction();
        try {
            CaseStudy::destroy($caseStudy->id);
            DB::commit();
            flash()->success(__('Case Study deleted successfully'));
            return redirect()->route('case_study.case_study.index')->with('success', 'Case Study deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->fail(__('Error. Cannot delete Case Study.'));
            return redirect()->route('case_study.case_study.index');
        }
    }
}
