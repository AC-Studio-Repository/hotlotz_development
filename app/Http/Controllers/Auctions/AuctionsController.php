<?php

namespace App\Http\Controllers\Auctions;

use DB;
use Hash;
use View;
use Carbon\Carbon;
use App\Helpers\MenuHelper;
use App\Helpers\SampleHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\AuctionRepository;
use App\Repositories\BannerRepository;
use App\Repositories\CMSRepository;
use App\Repositories\ItemRepository;
use App\Repositories\InternalAdvertRepository;

class AuctionsController extends Controller
{
    protected $itemRepository;
    protected $bannerRepository;
    protected $auctionRepository;
    protected $cmsRepository;
    protected $internalAdsRepository;

    public function __construct(
        ItemRepository $itemRepository,
        BannerRepository $bannerRepository,
        AuctionRepository $auctionRepository,
        CMSRepository $cmsRepository,
        InternalAdvertRepository $internalAdsRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->bannerRepository = $bannerRepository;
        $this->auctionRepository = $auctionRepository;
        $this->cmsRepository = $cmsRepository;
        $this->internalAdsRepository = $internalAdsRepository;
    }

    public function auctionCatalogue()
    {
        $menus = MenuHelper::getMenuAuction();
        $title = "Auction Catalogues";
        $today_open_time = SysConfig::getTodayOpenTime();
        $auctionItems = $this->auctionRepository->getForthComingAuctions();
        $comingItems = $this->itemRepository->getComingSoonLots();
        $whatWeSells = $this->itemRepository->getWhatWeSellCategories();
        $whatWeSellItems = collect();
        foreach ($whatWeSells as $item) {
            if($item['category_id'] == 2){
                $item['image'] = asset('ecommerce/images/Auctions/auction-catalogue/art.jpg');
                $whatWeSellItems->push($item);
            }
            if($item['category_id'] == 6){
                $item['image'] = asset('ecommerce/images/Auctions/auction-catalogue/jewels.jpg');
                $whatWeSellItems->push($item);
            }
            if($item['category_id'] == 1){
                $item['image'] = asset('ecommerce/images/Auctions/auction-catalogue/collectibles.jpg');
                $whatWeSellItems->push($item);
            }
            if($item['category_id'] == 3){
                $item['image'] = asset('ecommerce/images/Auctions/auction-catalogue/asianwoa.jpg');
                $whatWeSellItems->push($item);
            }
            if($item['category_id'] == 4){
                $item['image'] = asset('ecommerce/images/Auctions/auction-catalogue/designerluxury.jpg');
                $whatWeSellItems->push($item);
            }
            if($item['category_id'] == 9){
                $item['image'] = asset('ecommerce/images/Auctions/auction-catalogue/antiqueandfine.jpg');
                $whatWeSellItems->push($item);
            }
        }

        $data = [
            'menus' => $menus,
            'title' => $title,
            'today_open_time' => $today_open_time,
            'auctionItems' => $auctionItems,
            'comingItems' => $comingItems,
            'whatWeSells' => $whatWeSellItems,
        ];

        return view('pages.auctions.auction_catalogues', $data);
    }

    public function auctionCatalogueDetail()
    {
        $title = "Auction Catalogue Detail";
        $page_title = "Timed Auction | Jewellery & Watches";
        $menus = MenuHelper::getMenuAuction();
        $banner = "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/articles-and-events/banner.png";
        $caption = "Image caption";
        $slogon = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium.";
        $date = "THURSDAY 10 OCTOBER AT 10PM";
        $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra in leo vel pretium. Curabitur sed gravida turpis, vel vehicula mi. Vestibulum fringilla elementum posuere. Aenean hendrerit arcu posuere, sollicitudin risus eu, interdum ligula. Cras rutrum tincidunt ultrices. Phasellus nibh justo, lobortis et aliquet sed, molestie eu lectus. Suspendisse potenti. Praesent vel condimentum libero. Integer commodo sem non placerat fringilla. Sed vel purus elementum, lacinia tellus quis, porta dolor. Fusce dapibus tempor vehicula.";

        $marketplaceHightlightTitle = "Marketplace Highlights";
        $marketplaceHighlights = $this->itemRepository->getMarketplaceHighlights();
        $mailingLists = array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter');
        $today_open_time = SysConfig::getTodayOpenTime();

        $data = compact('menus', 'title', 'page_title', 'slogon', 'banner', 'caption', 'date', 'description', 'marketplaceHightlightTitle', 'marketplaceHighlights', 'mailingLists', 'today_open_time');
        return view('pages.auctions.auction_catalogue_detail', $data);
    }

    protected function mergeAuctionResultByMonth($a1, $a2)
    {
        $result[]['month_label'] = $a1;
        $result[]['auctions'] = $a2;

        return $result;
    }

    public function auctionResult()
    {
        $menus = MenuHelper::getMenuAuction();
        $title = "Auction Results";
        $today_open_time = SysConfig::getTodayOpenTime();
        $info = $this->bannerRepository->getAuctionResult();
        $cms = $this->cmsRepository->getAuctionResult();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'header' => $cms['title_header'],
            'blog' => $cms['title_blog'],
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => 'Marketplace Highlights',
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
            'auction_results' => $this->auctionRepository->getAuctionResults(),
        ];

        return view('pages.auctions.auction_result', $data);
    }

    public function auctionResultOld()
    {
        $date = Carbon::now();
        $month = $date->month;
        $this_year = $date->year;
        $this_month = $date->format('F');
        $offset = 0;
        $limit = 4;

        $past_1_month = Carbon::now()->subMonths(1)->format('m');
        $last_1_month = Carbon::now()->subMonths(1)->format('F');
        $past_2_month = Carbon::now()->subMonths(2)->format('m');
        $last_2_month = Carbon::now()->subMonths(2)->format('F');

        // $month_arr = [(string)$month, $past_1_month, $past_2_month];

        // $last_3_month_slider = [$month => $this_month, $past_1_month => $last_1_month, $past_2_month => $last_2_month];

        $this_month_result = $this->auctionRepository->getAuctionResultByMonth($this_year, $month);

        $past_1_month_result = $this->auctionRepository->getAuctionResultByMonth($this_year, $past_1_month);

        $past_2_month_result = $this->auctionRepository->getAuctionResultByMonth($this_year, $past_2_month);

        $menus = MenuHelper::getMenuAuction();
        $title = "Auction Results";
        $today_open_time = SysConfig::getTodayOpenTime();
        $info = $this->bannerRepository->getAuctionResult();
        $cms = $this->cmsRepository->getAuctionResult();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'header' => $cms['title_header'],
            'blog' => $cms['title_blog'],
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => 'Marketplace Highlights',
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'this_month_result' => $this_month_result,
            'past_1_month_result' => $past_1_month_result,
            'past_2_month_result' => $past_2_month_result,
            'this_month' => $this_month,
            'last_1_month' => $last_1_month,
            'last_2_month' => $last_2_month,
            'this_year' => $this_year,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2)
        ];

        return view('pages.auctions.auction_result', $data);
    }

    public function auctionResultLots() {
        $menus = MenuHelper::getMenuAuction();
        $title = "Auction Results Lots";
        $info = $this->bannerRepository->getAuctionResult();
        $cms = $this->cmsRepository->getAuctionResult();
        $today_open_time = SysConfig::getTodayOpenTime();
        $advertTitle = 'HOME CONTENTS';
        $advertContent = 'Moving? Relocating? We can help you selling your belongings.';
        $advertUrl = 'services.home-content';
        $advertLink = 'LEARN MORE';

        $data = [
            'menus' => $menus,
            'title' => $title,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'header' => $cms['title_header'],
            'blog' => $cms['title_blog'],
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'advertTitle' => $advertTitle,
            'advertContent' => $advertContent,
            'advertUrl' => $advertUrl,
            'advertLink' => $advertLink,
            'marketplaceHightlightTitle' => 'Marketplace Highlights',
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'today_open_time' => $today_open_time,
        ];
        return view('pages.auctions.auction_result_lots', $data);
    }

    public function pastCatalogue()
    {
        $title = "Auction Results";
        $menus = MenuHelper::getMenuAuction();
        $today_open_time = SysConfig::getTodayOpenTime();
        $info = $this->bannerRepository->getPastCatalogues();
        $cms = $this->cmsRepository->getPastCatalogues();
        $next_auction = $this->auctionRepository->getNextAuction();

        $offset = 0;
        $limit = 9;
        $show_pages = 0;
        $current_page = 0;

        // $past_auctions_count = $this->auctionRepository->getPastAuctionCount();

        // $show_pages = ceil($past_auctions_count/9);
        // $mod_items = $past_auctions_count % 9;
        // if($show_pages > 0) {
        //     $current_page = 1;
        // }

        $sale_types = Auction::getSaleTypes();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'next_auction' => $next_auction,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
            'today_open_time' => $today_open_time,
            'auctions' => $this->auctionRepository->getPastAuctionCatalogues($offset, $limit),
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            // 'past_auctions_count' => $past_auctions_count,
            'show_pages' => $show_pages,
            // 'mod_items' => $mod_items,
            'current_page' => $current_page,
            'sale_types' => $sale_types,
        ];

        return view('pages.auctions.past_catalogues', $data);
    }

    public function getPastAuctionPagerAjax(Request $request)
    {
        $offset = $request->offset;
        $limit = $request->limit;
        $past_auctions_count = $request->past_auctions_count;
        $per_page = $request->per_page;

        $past_auctions_count = $past_auctions_count;
        $show_pages = 0;
        $current_page = $request->current_page;

        $sale_type = $request->sale_type;

        $auctions = $this->auctionRepository->getPastAuctionCatalogues($offset, $limit, $sale_type);

        $show_pages = ceil($past_auctions_count/$per_page);
        $mod_items = $past_auctions_count % $per_page;

        $data = [
            'auctions' => $auctions,
            'past_auctions_count' => $past_auctions_count,
            'show_pages' => $show_pages,
            'mod_items' => $mod_items,
            'current_page' => $current_page,
            'per_page' => $per_page
        ];

        $returnHtml = View::make("pages.auctions.past_auctions_ajax")->with($data)->render();
        return $returnHtml;
    }

    public function getPastAuctionPagination(Request $request)
    {
        try {
            $atg_tenant_id = config('thesaleroom.atg_tenant_id');
            $auctions = Auction::where('is_closed', 'Y')
                ->select('id','type','full_path','title','important_information','sr_auction_id','sr_reference','timed_start');

            if(isset($request->sale_type) && $request->sale_type != 'all'){
                $auctions = $auctions->where('sale_type',$request->sale_type);
            }

            $pagination = 9;
            $auctions = $auctions->orderBy('timed_start', 'DESC')->paginate((int)$pagination);

            $returnHTML = view('pages.auctions.past_auctions_paginate', [
                'atg_tenant_id' => $atg_tenant_id,
                'auctions' => $auctions,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Get Past Catalogues Successfully', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function forthComing($slug)
    {
        $auction = Auction::where('coming_auction_url', $slug)->firstOrFail();

        $title = $auction->title;
        $page_title = $auction->title;
        $menus = MenuHelper::getMenuAuction();
        $banner = $auction->banner_full_path;
        $caption = null;
        $slogon = $auction->important_information;
        $description = $auction->auction_detail;
        $about = $auction->coming_auction_about;

        $consignment_deadline = $auction->consignment_deadline ? date('l j F', strtotime($auction->consignment_deadline)) : null;

        if(Carbon::now()->greaterThan(Carbon::create($auction->consignment_deadline))){
            $consignment_deadline = 'Consignment Closed';
        }

        if($auction->coming_auction_tick == 1){
            $consignment_deadline = 'Private Collection - No Further Consignment Required';
        }

        $consignment_info = $auction->consignment_info;
        $viewing_date_start = $auction->viewing_date_start;
        $viewing_date_end = $auction->viewing_date_end;
        $date = $auction->created_at;
        $marketplaceHightlightTitle = "Recently Consigned";
        $itemImage = $this->bannerRepository->getRandomImage();
        $marketplaceHighlights = $this->itemRepository->getMarketplaceHighlights();
        $mailingLists = array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter');
        $today_open_time = SysConfig::getTodayOpenTime();

        $auction_items = $this->auctionRepository->getComingAuctionItems($auction);

        $data = compact('menus', 'title', 'page_title', 'slogon', 'banner', 'caption', 'date', 'description', 'marketplaceHightlightTitle', 'marketplaceHighlights', 'itemImage', 'mailingLists', 'today_open_time', 'consignment_deadline', 'consignment_info', 'viewing_date_start', 'viewing_date_end', 'about', 'auction_items');
        return view('pages.auctions.auction_forthcoming_detail', $data);
    }
}
