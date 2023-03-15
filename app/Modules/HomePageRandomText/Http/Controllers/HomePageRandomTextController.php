<?php

namespace App\Modules\HomePageRandomText\Http\Controllers;

use Illuminate\Http\Request;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Models\TimeZone;
use App\Modules\HomePageRandomText\Http\Requests\StoreHomePageRandomTextRequest;
use App\Modules\HomePageRandomText\Http\Requests\UpdateHomePageRandomTextRequest;
use App\Modules\HomePageRandomText\Http\Requests\AjaxCreateContentManagement;
use App\Modules\HomePageRandomText\Http\Repositories\HomePageRandomTextRepository;
use App\Modules\HomePageRandomText\Models\HomePageRandomText;
use DB;
use Response;


class HomePageRandomTextController extends BaseController
{
    protected $homePageRandomTextRepository;
    public function __construct(HomePageRandomTextRepository $homePageRandomTextRepository){
        $this->homePageRandomTextRepository = $homePageRandomTextRepository;
    }

    public function index()
    {
        $random_texts = HomePageRandomText::all();

        $data = [
            'random_texts' => $random_texts,
        ];
        return view('home_page_random_text::index',$data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $random_texts = app(HomePageRandomText::class);
        
        $data = [
            'random_text' => $random_texts
        ];
        return view('home_page_random_text::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHomePageRandomTextRequest $request)
    {
        try {
            $random_text = HomePageRandomText::create($this->packData($request));
            flash()->success(__(':name has been created', ['name' => $random_text->title]));
            return redirect(route('home_page_random_text.home_page_random_texts.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the faq category
     *
     * @param Item $HomePageRandomText
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(HomePageRandomText $homePageRandomText)
    {
        return view('home_page_random_text::show', [
            'random_text' => $homePageRandomText
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(HomePageRandomText $homePageRandomText)
    {
        $homePageRandomText = $this->homePageRandomTextRepository->show('id', $homePageRandomText->id, [], true);
        
        $data = [
            'random_text' => $homePageRandomText
        ];
        return view('home_page_random_text::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHomePageRandomTextRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // prepare variables
            $payload = $this->packData($request);
            // update auction
            $this->homePageRandomTextRepository->update($id, $payload, true);
            DB::commit();

            flash()->success(__(':name has been updated', ['name' => HomePageRandomText::find($id)->title]));
            return redirect()->route('home_page_random_text.home_page_random_texts.index')->with('success', 'Random Text Updated Successfully!');
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
                $this->homePageRandomTextRepository->destroy($id);
                DB::commit();

                return redirect()->route('home_page_random_text.home_page_random_texts.index')->with('success', 'Random Text Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('home_page_random_text.home_page_random_texts.index')->with('fail', 'Random Text Deleting Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->homePageRandomTextRepository->restore($id);
            DB::commit();

            return redirect()->route('home_page_random_text.home_page_random_texts.index')->with('success', 'Random Text Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('home_page_random_text.home_page_random_texts.index')->with('fail', 'Random Text Activating Failed!');
        }
    }

    protected function packData($request)
    {
        $payload['title'] = $request->title;
        $payload['description'] = $request->description;
        $payload['link_url'] = $request->link_url;
       
        return $payload;
    }
}
