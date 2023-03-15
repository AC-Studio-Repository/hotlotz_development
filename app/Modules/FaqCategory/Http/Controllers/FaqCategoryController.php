<?php

namespace App\Modules\FaqCategory\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\FaqCategory\Http\Requests\StoreFaqCategoryRequest;
use App\Modules\FaqCategory\Http\Requests\UpdateFaqCategoryRequest;
use App\Modules\FaqCategory\Http\Requests\AjaxCreateContentManagement;
use App\Modules\FaqCategory\Http\Repositories\FaqCategoryRepository;
use App\Modules\FaqCategory\Models\FaqCategory;
use DB;
use Response;


class FaqCategoryController extends BaseController
{
    protected $faqCategoryRepository;
    public function __construct(FaqCategoryRepository $faqCategoryRepository){
        $this->faqCategoryRepository = $faqCategoryRepository;
    }

    public function index()
    {
        return view('faq_category::index');
    }

    public function showlist()
    {
        return view('faq_category::showlist');
    }

    public function categorylist()
    {
        $faqcategories = $this->faqCategoryRepository->all([], false, 10);

        $data = [
            'faqcategories' => $faqcategories,
        ];
        return view('faq_category::faq_category_list',$data);
    }

    public function blogList()
    {
        return view('faq_category::bloglist');
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faqcategory = app(FaqCategory::class);
        
        $data = [
            'faq_category' => $faqcategory
        ];
        return view('faq_category::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFaqCategoryRequest $request)
    {
        try {
            $faqcategory = FaqCategory::create($this->packData($request));
            flash()->success(__(':name has been created', ['name' => $faqcategory->getName()]));
            return redirect(route('faq_category.faqcategories.faqCategoryList'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the faq category
     *
     * @param Item $faqcategory
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(FaqCategory $faqcategory)
    {
        return view('faq_category::show', [
            'faqcategory' => $faqcategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FaqCategory $faqcategory)
    {
        $faqcategory = $this->faqCategoryRepository->show('id', $faqcategory->id, [], true);
        
        $data = [
            'faqcategory' => $faqcategory
        ];
        return view('faq_category::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFaqCategoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->faqCategoryRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => FaqCategory::find($id)->name]));
            return redirect()->route('faq_category.faqcategories.faqCategoryList')->with('success', 'FAQ Category Updated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
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
                $result = $this->faqCategoryRepository->destroy($id);
        
                if($result == 1) {
                    $this->faqCategoryRepository->destroy($id, 2);
                    DB::commit();
                    // $flash_message = 'FAQ Category Deactivated Successfully!';
                    // return redirect()->route('faq_category.faqcategories.faqCategoryList')->withDelete($flash_message);
                    return redirect()->route('faq_category.faqcategories.faqCategoryList')->with('success', 'FAQ Category Deactivated Successfully!');
                }else{
                    $flash_message = 'Dactivating Failed. This Category is used by other!';
                    return redirect()->route('faq_category.faqcategories.faqCategoryList')->withMessage($flash_message);
                }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('faq_category.faqcategories.faqCategoryList')->with('fail', 'FAQ Category Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->faqCategoryRepository->restore($id);
            DB::commit();

            return redirect()->route('faq_category.faqcategories.faqCategoryList')->with('success', 'FAQ Category Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('faq_category.faqcategories.faqCategoryList')->with('fail', 'FAQ Category Activating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['name'] = $request->name;
       
        return $payload;
    }
}
