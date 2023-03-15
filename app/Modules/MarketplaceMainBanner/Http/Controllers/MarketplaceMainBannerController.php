<?php

namespace App\Modules\MarketplaceMainBanner\Http\Controllers;

use DB;
use Response;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use Illuminate\Support\Facades\Storage;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Modules\MarketplaceMainBanner\Models\MarketplaceMainBanner;
use App\Modules\MarketplaceMainBanner\Http\Requests\StoreMarketplaceMainBannerRequest;
use App\Modules\MarketplaceMainBanner\Http\Requests\UpdateMarketplaceMainBannerRequest;
use App\Modules\MarketplaceMainBanner\Http\Repositories\MarketplaceMainBannerRepository;

class MarketplaceMainBannerController extends BaseController
{
    protected $marketplaceMainBannerRepository;
    public function __construct(MarketplaceMainBannerRepository $marketplaceMainBannerRepository){
        $this->marketplaceMainBannerRepository = $marketplaceMainBannerRepository;
    }


    public function index()
    {
        $marketplace_main_banners = $this->marketplaceMainBannerRepository->all([], false, 100);

        $data = [
            'marketplace_main_banners' => $marketplace_main_banners,
        ];

        return view('marketplace_main_banner::index',$data);
    }

    public function marketplaceMainBannerReordering(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['marketplace_main_banner_id'] as $key => $marketplace_main_banner_id) {
                $sequence_number = $key + 1;

                $this->marketplaceMainBannerRepository->update($marketplace_main_banner_id, ['order'=>$sequence_number], true);
            }
            DB::commit();

            flash()->success(__('Marketplace Home Banners are reordered Successfully!'));
            return redirect()->route('marketplace_main_banner.marketplace_main_banners.index');

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Marketplace Home Banners are reordered Failed!']));
            return redirect()->route('marketplace_main_banner.marketplace_main_banners.index')->with('fail', 'Marketplace Home Banners are reordered Failed!');
        }
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner = '';
        $marketplace_main_banner = app(MarketplaceMainBanner::class);

        $data = [
            'marketplace_main_banner' => $marketplace_main_banner,
            'banner' => $banner,
        ];
        return view('marketplace_main_banner::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarketplaceMainBannerRequest $request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $payload = $this->marketplaceMainBannerRepository->packData($request);
            // dd($payload);
            $marketplace_main_banner = $this->marketplaceMainBannerRepository->create($payload);

            if ($marketplace_main_banner) {

                $this->marketplaceMainBannerRepository->update($marketplace_main_banner->id, ['order'=>$marketplace_main_banner->id], true);

                if (isset($request->banner) && $request->banner != null) {
                    $banner = $request->banner;
                    $file_path = Storage::put('marketplace_main_banner/'.$marketplace_main_banner->id, $banner);
                    $file_name = $banner->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->marketplaceMainBannerRepository->update($marketplace_main_banner->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $marketplace_main_banner->caption]));
                return redirect(route('marketplace_main_banner.marketplace_main_banners.show', ['marketplace_main_banner' => $marketplace_main_banner ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Marketplace Home Banner Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the faq
     *
     * @param Item $faq
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(MarketplaceMainBanner $MarketplaceMainBanner)
    {
        return view('marketplace_main_banner::show', [
            'marketplace_main_banner' => $MarketplaceMainBanner
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketplaceMainBanner $marketplace_main_banner)
    {

        $data = [
            'marketplace_main_banner' => $marketplace_main_banner,
        ];
        return view('marketplace_main_banner::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateMarketplaceMainBannerRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->marketplaceMainBannerRepository->packData($request);
            // dd($payload);
            
            if (isset($request->banner) && $request->banner != null) {
                $banner = $request->banner;
                $file_path = Storage::put('marketplace_main_banner/'.$id, $banner);
                $file_name = $banner->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            $updated = $this->marketplaceMainBannerRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $marketplace_main_banner = MarketplaceMainBanner::find($id);
                flash()->success(__(':name has been updated', ['name' => $marketplace_main_banner->caption]));
                return redirect(route('marketplace_main_banner.marketplace_main_banners.show', ['marketplace_main_banner' => $marketplace_main_banner ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Marketplace Home Banner Update Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketplaceMainBanner $marketplace_main_banner)
    {
        // dd($marketplace_main_banner);
        try {
            $name = $marketplace_main_banner->name;
            $marketplace_main_banner->forceDelete();

            return response()->json([ 'status'=>'success', 'message' => $name.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
