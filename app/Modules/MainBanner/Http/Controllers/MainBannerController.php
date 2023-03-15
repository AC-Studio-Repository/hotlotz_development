<?php

namespace App\Modules\MainBanner\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\MainBanner\Http\Requests\StoreMainBannerRequest;
use App\Modules\MainBanner\Http\Requests\UpdateMainBannerRequest;
use App\Modules\MainBanner\Http\Repositories\MainBannerRepository;
use App\Modules\MainBanner\Models\MainBanner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class MainBannerController extends Controller
{
    protected $mainBannerRepository;
    public function __construct(MainBannerRepository $mainBannerRepository){
        $this->mainBannerRepository = $mainBannerRepository;
    }

    /**
     * Displays the main_banner index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $main_banners = $this->mainBannerRepository->all([], false, 10);

        return view('main_banner::index', [
            'main_banner' => app(MainBanner::class),
            'main_banners' => $main_banners
        ]);
    }

    public function mainBannerReordering(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['mainbanner_id'] as $key => $main_banner_id) {
                $sequence_number = $key + 1;

                $this->mainBannerRepository->update($main_banner_id, ['order'=>$sequence_number], true);
            }
            DB::commit();

            flash()->success(__('Main Banner are reordered Successfully!'));
            return redirect()->route('main_banner.main_banners.index');

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Main Banner are reordered Failed!']));
            return redirect()->route('main_banner.main_banners.index')->with('fail', 'Main Banner are reordered Failed!');
        }
    }

    /**
     * Displays the create new main_banner view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('main_banner::create', [
            'main_banner' => app(MainBanner::class),
        ]);
    }

    /**
     * @param CreateMainBanner $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreMainBannerRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->mainBannerRepository->packData($request);
            $main_banner = $this->mainBannerRepository->create($payload);

            if ($main_banner) {

                $this->mainBannerRepository->update($main_banner->id, ['order'=>$main_banner->id], true);

                if (isset($request->banner_image)) {
                    $banner_image = $request->banner_image;
                    $file_path = Storage::put('main_banner/'.$main_banner->id, $banner_image);
                    // $file_name = str_replace('main_banner/'.$main_banner->id.'/', '', $file_path);
                    $file_name = $banner_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->mainBannerRepository->update($main_banner->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':main_title has been created', ['main_title' => $main_banner->main_title]));
                return redirect(route('main_banner.main_banners.show', ['main_banner' => $main_banner ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Main Banner Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the main_banner
     *
     * @param MainBanner $main_banner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(MainBanner $main_banner)
    {
        $main_banner = $this->mainBannerRepository->show('id', $main_banner->id, [], false);

        return view('main_banner::show', [
            'main_banner' => $main_banner,
        ]);
    }

    /**
     * @param MainBanner $main_banner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(MainBanner $main_banner)
    {
        $main_banners = $this->mainBannerRepository->show('id', $main_banner->id, [], false);

        return view('main_banner::edit', [
            'main_banner' => $main_banners,
        ]);
    }

    /**
     * Saves updates to an existing main_banner
     *
     * @param MainBanner       $main_banner
     * @param UpdateMainBanner $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateMainBannerRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->mainBannerRepository->packData($request);
            if (isset($request->banner_image)) {
                $banner_image = $request->banner_image;
                $file_path = Storage::put('main_banner/'.$id, $banner_image);
                $file_name = $banner_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            $updated = $this->mainBannerRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $main_banner = MainBanner::find($id);
                flash()->success(__(':name has been updated', ['name' => $main_banner->main_title]));
                return redirect(route('main_banner.main_banners.show', ['main_banner' => $main_banner ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Main Banner Update Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a main_banner
     *
     * @param MainBanner $main_banner
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(MainBanner $main_banner)
    {
        try {
            $main_title = $main_banner->main_title;
            $main_banner->delete();

            return response()->json([ 'status'=>'success', 'message' => $main_title.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
