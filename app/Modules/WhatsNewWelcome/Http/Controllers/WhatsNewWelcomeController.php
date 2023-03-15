<?php

namespace App\Modules\WhatsNewWelcome\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\WhatsNewWelcome\Http\Requests\StoreWhatsNewWelcomeRequest;
use App\Modules\WhatsNewWelcome\Http\Requests\UpdateWhatsNewWelcomeRequest;
use App\Modules\WhatsNewWelcome\Http\Repositories\WhatsNewWelcomeRepository;
use App\Modules\WhatsNewWelcome\Models\WhatsNewWelcome;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class WhatsNewWelcomeController extends Controller
{
    protected $whatsNewWelcomeRepository;
    public function __construct(WhatsNewWelcomeRepository $whatsNewWelcomeRepository){
        $this->whatsNewWelcomeRepository = $whatsNewWelcomeRepository;
    }

    /**
     * Displays the whats_new_welcome index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $welcome = WhatsNewWelcome::first();
        $whats_new_welcome = app(WhatsNewWelcome::class);
        $id = 0;
        if($welcome != null){
            $whats_new_welcome = $welcome;
            $id = $welcome->id;
        }
        // dd($whats_new_welcome);

        return view('whats_new_welcome::index', [
            'whats_new_welcome' => $whats_new_welcome,
            'id' => $id
        ]);
    }

    /**
     * Displays the create new whats_new_welcome view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('whats_new_welcome::create', [
            'whats_new_welcome' => app(WhatsNewWelcome::class),
        ]);
    }

    /**
     * @param CreateWhatsNewWelcome $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreWhatsNewWelcomeRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {

            $payload = $this->whatsNewWelcomeRepository->packData($request);
            // dd($payload);

            $whats_new_welcome = $this->whatsNewWelcomeRepository->create($payload);

            if ($whats_new_welcome) {
                if (isset($request->welcome_image)) {
                    $welcome_image = $request->welcome_image;
                    $file_path = Storage::put('whats_new_welcome/'.$whats_new_welcome->id, $welcome_image);
                    $file_name = $welcome_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->whatsNewWelcomeRepository->update($whats_new_welcome->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':title has been created', ['title' => $whats_new_welcome->title]));
                return redirect(route('whats_new_welcome.whats_new_welcomes.show', ['whats_new_welcome' => $whats_new_welcome ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => "What's New Welcome Create Failed!"]));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the whats_new_welcome
     *
     * @param WhatsNewWelcome $whats_new_welcome
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(WhatsNewWelcome $whats_new_welcome)
    {
        return view('whats_new_welcome::show', [
            'whats_new_welcome' => $whats_new_welcome,
        ]);
    }

    /**
     * @param WhatsNewWelcome $whats_new_welcome
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(WhatsNewWelcome $whats_new_welcome)
    {
        $whats_new_welcomes = $this->whatsNewWelcomeRepository->show('id', $whats_new_welcome->id, [], false);

        return view('whats_new_welcome::edit', [
            'whats_new_welcome' => $whats_new_welcomes,
        ]);
    }

    /**
     * Saves updates to an existing whats_new_welcome
     *
     * @param WhatsNewWelcome       $whats_new_welcome
     * @param UpdateWhatsNewWelcome $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateWhatsNewWelcomeRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->whatsNewWelcomeRepository->packData($request);
            if (isset($request->welcome_image)) {
                $welcome_image = $request->welcome_image;
                $file_path = Storage::put('whats_new_welcome/'.$id, $welcome_image);
                $file_name = $welcome_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            $updated = $this->whatsNewWelcomeRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $whats_new_welcome = WhatsNewWelcome::find($id);
                flash()->success(__(':name has been updated', ['name' => $whats_new_welcome->title]));
                return redirect(route('whats_new_welcome.whats_new_welcomes.show', ['whats_new_welcome' => $whats_new_welcome ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => "What's New Welcome Update Failed!"]));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a whats_new_welcome
     *
     * @param WhatsNewWelcome $whats_new_welcome
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(WhatsNewWelcome $whats_new_welcome)
    {
        try {
            $title = $whats_new_welcome->title;
            $whats_new_welcome->delete();

            return response()->json([ 'status'=>'success', 'message' => $title.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
