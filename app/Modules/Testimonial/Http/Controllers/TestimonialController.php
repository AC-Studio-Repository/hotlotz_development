<?php

namespace App\Modules\Testimonial\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\Testimonial\Http\Requests\StoreTestimonialRequest;
use App\Modules\Testimonial\Http\Requests\UpdateTestimonialRequest;
use App\Modules\Testimonial\Http\Requests\AjaxCreateContentManagement;
use App\Modules\Testimonial\Http\Repositories\TestimonialRepository;
use App\Modules\Testimonial\Models\Testimonial;
use DB;
use Response;


class TestimonialController extends BaseController
{
    protected $testimonialRepository;
    public function __construct(TestimonialRepository $testimonialRepository){
        $this->testimonialRepository = $testimonialRepository;
    }

    public function index()
    {
        $testimonials = $this->testimonialRepository->all([], false, 10);

        $data = [
            'testimonials' => $testimonials,
        ];
        return view('testimonial::index',$data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testimonial = app(Testimonial::class);
        
        $data = [
            'testimonial' => $testimonial
        ];
        return view('testimonial::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTestimonialRequest $request)
    {
        try {
            $testimonial = Testimonial::create($this->packData($request));
            flash()->success(__(':name \'s testimonial has been created', ['name' => $testimonial->author]));
            return redirect(route('testimonial.testimonials.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the Testimonial
     *
     * @param Item $Testimonial
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Testimonial $testimonial)
    {
        return view('testimonial::show', [
            'testimonial' => $testimonial
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $testimonial)
    {
        $testimonial = $this->testimonialRepository->show('id', $testimonial->id, [], true);
        
        $data = [
            'testimonial' => $testimonial
        ];
        return view('testimonial::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTestimonialRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->testimonialRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name \'s testimonial has been updated', ['name' => Testimonial::find($id)->author]));
            return redirect()->route('testimonial.testimonials.index')->with('success', 'Testimonial Updated Successfully!');
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
                $result = $this->testimonialRepository->destroy($id);
                if($result == 1) {
                    $this->testimonialRepository->destroy($id, 2);
                    DB::commit();
                    // $flash_message = 'Testimonial Deactivated Successfully!';
                    // return redirect()->route('testimonial.testimonials.index')->withDelete($flash_message);
                    return redirect()->route('testimonial.testimonials.index')->with('success', 'Testimonial Deactivated Successfully!');
                }else{
                    $flash_message = 'Dactivating Failed. This Testimonial is used to show on Home Page!';
                    return redirect()->route('testimonial.testimonials.index')->withMessage($flash_message);
                }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('testimonial.testimonials.index')->with('fail', 'Testimonial Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->testimonialRepository->restore($id);
            DB::commit();

            return redirect()->route('testimonial.testimonials.index')->with('success', 'Testimonial Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('testimonial.testimonials.index')->with('fail', 'Testimonial Activating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['quote'] = $request->quote;
        $payload['author'] = $request->author;
       
        return $payload;
    }
}
