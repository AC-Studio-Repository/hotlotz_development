<?php

namespace App\Modules\WhatsNewArticleOne\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\WhatsNewArticleOne\Http\Requests\StoreWhatsNewArticleOneRequest;
use App\Modules\WhatsNewArticleOne\Http\Requests\UpdateWhatsNewArticleOneRequest;
use App\Modules\WhatsNewArticleOne\Http\Repositories\WhatsNewArticleOneRepository;
use App\Modules\WhatsNewArticleOne\Models\WhatsNewArticleOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class WhatsNewArticleOneController extends Controller
{
    protected $whatsNewArticleOneRepository;
    public function __construct(WhatsNewArticleOneRepository $whatsNewArticleOneRepository){
        $this->whatsNewArticleOneRepository = $whatsNewArticleOneRepository;
    }

    /**
     * Displays the whats_new_article_one index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $article_one = WhatsNewArticleOne::first();
        $whats_new_article_one = app(WhatsNewArticleOne::class);
        $id = 0;
        if($article_one != null){
            $whats_new_article_one = $article_one;
            $id = $article_one->id;
        }
        // dd($whats_new_article_one);

        return view('whats_new_article_one::index', [
            'whats_new_article_one' => $whats_new_article_one,
            'id' => $id
        ]);
    }

    /**
     * Displays the create new whats_new_article_one view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('whats_new_article_one::create', [
            'whats_new_article_one' => app(WhatsNewArticleOne::class),
        ]);
    }

    /**
     * @param CreateWhatsNewArticleOne $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreWhatsNewArticleOneRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {

            $payload = $this->whatsNewArticleOneRepository->packData($request);
            // dd($payload);

            $whats_new_article_one = $this->whatsNewArticleOneRepository->create($payload);

            if ($whats_new_article_one) {
                if (isset($request->whats_new_image)) {
                    $whats_new_image = $request->whats_new_image;
                    $file_path = Storage::put('whats_new_article_one/'.$whats_new_article_one->id, $whats_new_image);
                    $file_name = $whats_new_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->whatsNewArticleOneRepository->update($whats_new_article_one->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':title has been created', ['title' => $whats_new_article_one->title]));
                return redirect(route('whats_new_article_one.whats_new_article_ones.show', ['whats_new_article_one' => $whats_new_article_one ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => "What's New Article One Create Failed!"]));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the whats_new_article_one
     *
     * @param WhatsNewArticleOne $whats_new_article_one
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(WhatsNewArticleOne $whats_new_article_one)
    {
        return view('whats_new_article_one::show', [
            'whats_new_article_one' => $whats_new_article_one,
        ]);
    }

    /**
     * @param WhatsNewArticleOne $whats_new_article_one
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(WhatsNewArticleOne $whats_new_article_one)
    {
        $whats_new_article_ones = $this->whatsNewArticleOneRepository->show('id', $whats_new_article_one->id, [], false);

        return view('whats_new_article_one::edit', [
            'whats_new_article_one' => $whats_new_article_ones,
        ]);
    }

    /**
     * Saves updates to an existing whats_new_article_one
     *
     * @param WhatsNewArticleOne       $whats_new_article_one
     * @param UpdateWhatsNewArticleOne $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateWhatsNewArticleOneRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->whatsNewArticleOneRepository->packData($request);
            if (isset($request->whats_new_image)) {
                $whats_new_image = $request->whats_new_image;
                $file_path = Storage::put('whats_new_article_one/'.$id, $whats_new_image);
                $file_name = $whats_new_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            $updated = $this->whatsNewArticleOneRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $whats_new_article_one = WhatsNewArticleOne::find($id);
                flash()->success(__(':name has been updated', ['name' => $whats_new_article_one->title]));
                return redirect(route('whats_new_article_one.whats_new_article_ones.show', ['whats_new_article_one' => $whats_new_article_one ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => "What's New Article One Update Failed!"]));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a whats_new_article_one
     *
     * @param WhatsNewArticleOne $whats_new_article_one
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(WhatsNewArticleOne $whats_new_article_one)
    {
        try {
            $title = $whats_new_article_one->title;
            $whats_new_article_one->delete();

            return response()->json([ 'status'=>'success', 'message' => $title.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
