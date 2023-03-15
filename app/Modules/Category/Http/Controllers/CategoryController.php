<?php

namespace App\Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Category\Http\Requests\StoreCategoryRequest;
use App\Modules\Category\Http\Requests\UpdateCategoryRequest;
use App\Modules\Category\Http\Repositories\CategoryRepository;
use App\Modules\Category\Http\Resources\CategoryDatatableResource;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Models\CategoryProperty;
// use Yajra\Datatables\Datatables;
use DB;
use App\Helpers\NHelpers;
use Illuminate\Http\Response;
use App\Events\CategoryUpdatedEvent;

class CategoryController extends Controller
{
    protected $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Displays the category index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = $this->categoryRepository->all([], false, 10);

        return view('category::index', [
            'categories' => $categories
        ]);
    }

    /**
     * Displays the create new category view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('category::create', [
            'category' => app(Category::class),
            'parent_categories' => ['0'=>'---Select Parent Category---'] + Category::pluck('name','id')->toArray(),
        ]);
    }

    /**
     * @param CreateCategory $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreCategoryRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->packData($request);
            // dd($payload);
            $category = $this->categoryRepository->create($payload);
            
            DB::commit();
            flash()->success(__(':name has been created', ['name' => Category::getName()]));

            // return redirect(route('category.categories.index'));
            return redirect(route('category.categories.show', ['category' => $category]));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the category
     *
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Category $category)
    {
        $category = $this->categoryRepository->show('id', $category->id, [], false);

        return view('category::show', [
            'category' => $category,
            'field_types' => CategoryProperty::getFieldType(),
            // 'req_lists' => ['Required'=>'Required','Optional'=>'Optional'],
            'parent_categories' => ['0'=>'---Select Parent Category---'] + Category::where('id','!=',$category->id)->pluck('name','id')->toArray()
        ]);
    }

    /**
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Category $category)
    {
        $categories = $this->categoryRepository->show('id', $category->id, [], false);

        $categoryproperties = CategoryProperty::where('category_id',$category->id)->whereNull('deleted_at')->get();
        $category_properties = [];
        foreach ($categoryproperties as $key => $value) {
            $category_properties[] = [
                'id' => $value->id,
                'key' => $value->key,
                'value' => ($value->value != null)?(explode(',', $value->value)):'',
                'field_type' => $value->field_type,
                'is_required' => $value->is_required,
                'is_filter' => $value->is_filter,
            ];
        }
        // dd($category_properties);

        return view('category::edit', [
            'category' => $categories,
            'field_types' => CategoryProperty::getFieldType(),
            'categoryproperties' => $category_properties,
            'parent_categories' => ['0'=>'---Select Parent Category---'] + Category::where('id','!=',$category->id)->pluck('name','id')->toArray()
        ]);
    }

    /**
     * Saves updates to an existing category
     *
     * @param Category       $category
     * @param UpdateCategory $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->packData($request);
            $result = $this->categoryRepository->update($id, $payload, true);

            // if($result){
            //     if(isset($request->subcategory) && strlen($request->subcategory) > 0){
            //         $subcategories = explode(',', $request->subcategory);
            //         // $subcategory_ids = [];
            //         foreach ($subcategories as $subcategory) {
            //             // check Subcategory already exists
            //             $check_subcategory = DB::table('categories')->where('parent_id',$id)->where('name', '=', $subcategory)->count();
            //             // dd($check_subcategory);

            //             if ($check_subcategory == 0) { // Subcategory not exists
            //                 $tag_insert = $subcategory_insert = array('name' => $subcategory,'parent_id'=>$category->id) + NHelpers::created_updated_at_by();
            //                 $subcategory_id = DB::table('categories')->insertGetId($subcategory_insert);
            //             }                        
            //             // $subcategory_ids[] = $subcategory_id; 
            //         }
            //     }
            // }

            DB::commit();
            event(new CategoryUpdatedEvent(Category::find($id),'Customer Details'));

            flash()->success(__(':name has been updated', ['name' => Category::getNameById($id)]));

            return redirect(route('category.categories.show', ['category' => Category::find($id) ]));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a category
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Category $category)
    {
        try {
            $name = $category->name;
            $category->delete();

            flash()->warning(__(':name has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('category.categories.index'));
    }

    protected function packData($request)
    {
        $payload['name'] = $request['name']; //*
        // if(isset($request['parent_id'])){
        //     $payload['parent_id'] = $request['parent_id'];
        // }

        return $payload;
    }

    public function categoryPropertyUpdate($category_id, Request $request)
    {
        DB::beginTransaction();
        try{
            $inputs = $request->all();
            // dd($inputs);

            $existing_ids = DB::table('category_properties')->whereNull('deleted_at')->where('category_id',$category_id)->pluck('id')->all();
            // dd($existing_ids);

            foreach ($existing_ids as $key => $id) {
                if( !isset($inputs['property_id']) || (isset($inputs['property_id']) && !in_array( $id, $inputs['property_id'] ) ) ){
                    // dd($id);
                    DB::table('category_properties')->where('id', $id)->delete();
                }
            }

            if(isset($inputs['property_id'])){
                foreach ($inputs['property_id'] as $key => $property_id) {
                    // dd($property_id);
                    $category_property = [
                        'category_id' => $category_id,
                        'key' => $inputs['key'][$key],
                        'value' => $inputs['value'][$key],
                        'field_type' => $inputs['field_type'][$key],
                        'is_required' => $inputs['is_required'][$key],
                        'is_filter' => $inputs['is_filter'][$key],
                    ];

                    // dd(in_array( $property_id, $existing_ids ));
                    if( in_array( $property_id, $existing_ids ) && $property_id != 0 ){
                        DB::table('category_properties')->where('id', $property_id)->update($category_property + NHelpers::updated_at_by());
                    }else{
                        DB::table('category_properties')->insert($category_property + NHelpers::created_updated_at_by());
                    }
                }
            }

            DB::commit();
            event(new CategoryUpdatedEvent(Category::find($category_id),'Customer Properties'));

            flash()->success(__('Properties of :name has been updated', ['name' => Category::getNameById($category_id)]));

            return redirect(route('category.categories.edit', ['category' => Category::find($category_id) ]));

        } catch (Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function getSubCategory($id)
    {
        try {
            $category = Category::where('id',$id)->first();
            $sub_category = CategoryProperty::where('category_id',$id)->where('key','Sub Category')->pluck('value')->first();
            $subcategories = [];
            $subcategories = [''=>'--- Select Sub Category ---'];
            if($sub_category && $sub_category != null){
                $sub_category = explode(',', $sub_category);

                foreach ($sub_category as $key => $value) {
                    $subcategories[$value] = $value;
                }
            }

            $data = [];
            $data['category_name'] = $category?$category->name:null;
            $data['subcategories'] = $subcategories;

            return \Response::json(array('status'=>'success','message'=>'Get Subcategory Successfully.','data'=>$data));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }
}
