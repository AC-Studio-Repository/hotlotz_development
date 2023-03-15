<?php

namespace App\Modules\MarketplaceBanner\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\MarketplaceBanner\Http\Requests\StoreMarketplaceBannerRequest;
use App\Modules\MarketplaceBanner\Http\Requests\UpdateMarketplaceBannerRequest;
use App\Modules\MarketplaceBanner\Http\Repositories\MarketplaceBannerRepository;
use App\Modules\MarketplaceBanner\Models\MarketplaceBanner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class MarketplaceBannerController extends Controller
{
    protected $marketplaceBannerRepository;
    public function __construct(MarketplaceBannerRepository $marketplaceBannerRepository){
        $this->marketplaceBannerRepository = $marketplaceBannerRepository;
    }

    /**
     * Displays the marketplace_banner index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $marketplace_banners = $this->marketplaceBannerRepository->all([], false, 10);

        return view('marketplace_banner::index', [
            'marketplace_banner' => app(MarketplaceBanner::class),
            'marketplace_banners' => $marketplace_banners
        ]);
    }

    public function marketplaceBannerReordering(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['marketplacebanner_id'] as $key => $marketplace_banner_id) {
                $sequence_number = $key + 1;

                $this->marketplaceBannerRepository->update($marketplace_banner_id, ['order'=>$sequence_number], true);
            }
            DB::commit();

            flash()->success(__('Marketplace Banner are reordered Successfully!'));
            return redirect()->route('marketplace_banner.marketplace_banners.index');

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Marketplace Banner are reordered Failed!']));
            return redirect()->route('marketplace_banner.marketplace_banners.index')->with('fail', 'Marketplace Banner are reordered Failed!');
        }
    }

    /**
     * Displays the create new marketplace_banner view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('marketplace_banner::create', [
            'marketplace_banner' => app(MarketplaceBanner::class),
        ]);
    }

    /**
     * @param CreateMarketplaceBanner $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreMarketplaceBannerRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->marketplaceBannerRepository->packData($request);
            // dd($payload);
            $marketplace_banner = $this->marketplaceBannerRepository->create($payload);

            if ($marketplace_banner) {

                $this->marketplaceBannerRepository->update($marketplace_banner->id, ['order'=>$marketplace_banner->id], true);

                if (isset($request->banner_image)) {
                    $banner_image = $request->banner_image;
                    $file_path = Storage::put('marketplace_banner/'.$marketplace_banner->id, $banner_image);
                    $file_name = $banner_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->marketplaceBannerRepository->update($marketplace_banner->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':marketplace_title has been created', ['name' => 'Marketplace Banner '.$marketplace_banner->id]));
                return redirect(route('marketplace_banner.marketplace_banners.show', ['marketplace_banner' => $marketplace_banner ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Marketplace Banner Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the marketplace_banner
     *
     * @param MarketplaceBanner $marketplace_banner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(MarketplaceBanner $marketplace_banner)
    {
        $marketplace_banner = $this->marketplaceBannerRepository->show('id', $marketplace_banner->id, [], false);

        return view('marketplace_banner::show', [
            'marketplace_banner' => $marketplace_banner,
        ]);
    }

    /**
     * @param MarketplaceBanner $marketplace_banner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(MarketplaceBanner $marketplace_banner)
    {
        $marketplace_banners = $this->marketplaceBannerRepository->show('id', $marketplace_banner->id, [], false);

        return view('marketplace_banner::edit', [
            'marketplace_banner' => $marketplace_banners,
        ]);
    }

    /**
     * Saves updates to an existing marketplace_banner
     *
     * @param MarketplaceBanner       $marketplace_banner
     * @param UpdateMarketplaceBanner $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateMarketplaceBannerRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->marketplaceBannerRepository->packData($request);
            if (isset($request->banner_image)) {
                $banner_image = $request->banner_image;
                $file_path = Storage::put('marketplace_banner/'.$id, $banner_image);
                $file_name = $banner_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            $updated = $this->marketplaceBannerRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $marketplace_banner = MarketplaceBanner::find($id);
                flash()->success(__(':name has been updated', ['name' => 'Marketplace Banner '.$marketplace_banner->id]));
                return redirect(route('marketplace_banner.marketplace_banners.show', ['marketplace_banner' => $marketplace_banner ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Marketplace Banner Update Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a marketplace_banner
     *
     * @param MarketplaceBanner $marketplace_banner
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(MarketplaceBanner $marketplace_banner)
    {
        try {
            $id = $marketplace_banner->id;
            $marketplace_banner->delete();

            return response()->json([ 'status'=>'success', 'message' => 'Marketplace Banner '.$id.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
