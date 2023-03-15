<?php

namespace App\Http\Controllers\Footer;

use App\Helpers\MenuHelper;
use App\Http\Controllers\Controller;
use App\Modules\ContentManagement\Models\TermsAndConditions;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\AuctionRepository;
use App\Repositories\BannerRepository;
use App\Repositories\CMSRepository;
use App\Repositories\FooterRepository;
use App\Repositories\ItemRepository;
use App\Repositories\CaseStudyRepository;
use App\Repositories\InternalAdvertRepository;
use DB;
use Hash;

class FooterController extends Controller
{

    protected $cmsRepository;
    protected $bannerRepository;
    protected $itemRepository;
    protected $auctionRepository;
    protected $footerRepository;
    protected $caseStudyRepository;
    protected $internalAdsRepository;

    public function __construct(
        CMSRepository $cmsRepository,
        AuctionRepository $auctionRepository,
        BannerRepository $bannerRepository,
        ItemRepository $itemRepository,
        FooterRepository $footerRepository,
        CaseStudyRepository $caseStudyRepository,
        InternalAdvertRepository $internalAdsRepository
    ){
        $this->cmsRepository = $cmsRepository;
        $this->bannerRepository = $bannerRepository;
        $this->itemRepository = $itemRepository;
        $this->auctionRepository = $auctionRepository;
        $this->footerRepository = $footerRepository;
        $this->caseStudyRepository = $caseStudyRepository;
        $this->internalAdsRepository = $internalAdsRepository;
    }

    public function terms()
    {
        //TODO T&C Repository
        $title = "Terms & Conditions";
        $menus = MenuHelper::getMenuFooter();
        $today_open_time = SysConfig::getTodayOpenTime();
        $termsandconditions = TermsAndConditions::select('id','value','file_path','full_path')->first();

        $data = [
            'title' => $title,
            'menus' => $menus,
            'termsandconditions' => $termsandconditions,
            'today_open_time' => $today_open_time
        ];

        return view('pages.terms', $data);
    }

    public function policies()
    {
        $title = "Policies";
        $menus = MenuHelper::getMenuFooter();
        $today_open_time = SysConfig::getTodayOpenTime();
        $policies = $this->footerRepository->getPolicy();

        $data = [
            'title' => $title,
            'menus' => $menus,
            'policies' => $policies,
            'today_open_time' => $today_open_time
        ];

        return view('pages.policies', $data);
    }

    public function careers()
    {
        $title = "Careers";
        $menus = MenuHelper::getMenuFooter();
        $today_open_time = SysConfig::getTodayOpenTime();

        $sideMarketItems = $this->itemRepository->getSideMarketItems();
        $items = $this->itemRepository->getValuationItems();
        $marketPlaces = $this->itemRepository->getMarketplaceItems();

        $info = $this->bannerRepository->getCareersInfo();
        $cms = $this->cmsRepository->getCareersInfo();
        $cmsBlogs = $this->cmsRepository->getCareerBlog();
        $careers = $this->footerRepository->getCareers();

        $data = [
            'title' => $title,
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'today_open_time' => $today_open_time,
            'items' => $items,
            'sideMarketItems' => $sideMarketItems,
            'marketPlaces' => $marketPlaces,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'careers' => $careers,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.careers', $data);
    }

    public function mediaResource()
    {
        $title = 'Media Resources';
        $menus = MenuHelper::getMenuFooter();

        $info = $this->bannerRepository->getMediaResource();
        $cms = $this->cmsRepository->getMediaResource();
        $cmsBlogs = $this->cmsRepository->getMediaResourceBlog();
        $media_resources = $this->footerRepository->getMediaResource();

        $today_open_time = SysConfig::getTodayOpenTime();
        $data = [
            'title' => $title,
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'today_open_time' => $today_open_time,
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'country_1' => $cms['country_1'],
            'email_1' => $cms['email_1'],
            'country_2' => $cms['country_2'],
            'email_2' => $cms['email_2'],
            'file_path' => $cms['file_path'],
            'cmsBlogs' => $cmsBlogs,
            'media_resources' => $media_resources,
        ];
        return view('pages.media-resource', $data);
    }

}
