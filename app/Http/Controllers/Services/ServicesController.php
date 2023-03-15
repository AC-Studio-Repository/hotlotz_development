<?php

namespace App\Http\Controllers\Services;

use App\Helpers\MenuHelper;
use App\Http\Controllers\Controller;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\AuctionRepository;
use App\Repositories\BannerRepository;
use App\Repositories\BlogRepository;
use App\Repositories\CaseStudyRepository;
use App\Repositories\CMSRepository;
use App\Repositories\ContactRepository;
use App\Repositories\InternalAdvertRepository;
use App\Repositories\ItemRepository;
use App\Repositories\MarketplaceRepository;
use App\Repositories\TestimonialRepository;
use DB;
use Hash;

class ServicesController extends Controller
{
    protected $itemRepository;
    protected $bannerRepository;
    protected $auctionRepository;
    protected $testimonialRepository;
    protected $blogRepository;
    protected $marketplaceRepository;
    protected $contactRepository;
    protected $cmsRepository;
    protected $caseStudyRepository;
    protected $internalAdsRepository;

    public function __construct(
        ItemRepository $itemRepository,
        BannerRepository $bannerRepository,
        AuctionRepository $auctionRepository,
        TestimonialRepository $testimonialRepository,
        BlogRepository $blogRepository,
        MarketplaceRepository $marketplaceRepository,
        ContactRepository $contactRepository,
        CMSRepository $cmsRepository,
        CaseStudyRepository $caseStudyRepository,
        InternalAdvertRepository $internalAdsRepository
    )
    {
        $this->itemRepository = $itemRepository;
        $this->bannerRepository = $bannerRepository;
        $this->auctionRepository = $auctionRepository;
        $this->testimonialRepository = $testimonialRepository;
        $this->blogRepository = $blogRepository;
        $this->marketplaceRepository = $marketplaceRepository;
        $this->contactRepository = $contactRepository;
        $this->cmsRepository = $cmsRepository;
        $this->caseStudyRepository = $caseStudyRepository;
        $this->internalAdsRepository = $internalAdsRepository;
    }

    public function whatWeSell(){

        $menus = MenuHelper::getMenuService();
        $title = "What We Sell";
        $today_open_time = SysConfig::getTodayOpenTime();
        $info = $this->bannerRepository->getWhatWeSell();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),

            'items' => $this->itemRepository->getWhatWeSellCategories(),
        ];

        return view('pages.services.what_we_sell', $data);
    }

    public function whatwesellItems($id)
    {
        $whatwesell_data = $this->itemRepository->getWhatWeSellCategory($id);

        if($whatwesell_data == null){
            return abort(404);
        }
        $title = $whatwesell_data->title;
        $menus = MenuHelper::getMenuWhatWeSell();
        $info = $this->bannerRepository->getWhatWeSellDetail($id);
        $categoryhighlightTitle = 'Highlights';
        $categoryHighlights = $this->itemRepository->getWhatWeSellCategoryHighlights($id);

        $next_auction = $this->auctionRepository->getNextAuction();
        $today_open_time = SysConfig::getTodayOpenTime();

        $cms = $this->cmsRepository->getWhatWeSell($id);
        $cmsBlogs = $this->cmsRepository->getWhatWeSellBlog($id);
        $keyContacts = $this->cmsRepository->getWhatWeSellKeyContact($id);

        $data = [
            'title' => $title,
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'whatwesell_data' => $whatwesell_data,
            'categoryhighlightTitle' => $categoryhighlightTitle,
            'categoryHighlights' => $categoryHighlights,
            'keyContacts' => $keyContacts,
            'next_auction' => $next_auction,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'today_open_time' => $today_open_time,
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'keyContacts' => $keyContacts,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.services.whatwesell_detail', $data);
    }

    public function auction()
    {

        $menus = MenuHelper::getMenuService();
        $title = "Auctions";
        $today_open_time = SysConfig::getTodayOpenTime();

        $info = $this->bannerRepository->getAuction();
        $cms = $this->cmsRepository->getAuction();
        $cmsBlogs = $this->cmsRepository->getAuctionBlog();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.services.auctions', $data);
    }

    public function marketplace()
    {

        $menus = MenuHelper::getMenuService();
        $title = "Marketplace";
        $today_open_time = SysConfig::getTodayOpenTime();
        $info = $this->bannerRepository->getMarketplace();
        $cms = $this->cmsRepository->getMarketplace();
        $cmsBlogs = $this->cmsRepository->getMarketplaceBlog();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.services.marketplace', $data);
    }

    public function privateCollection()
    {

        $menus = MenuHelper::getMenuService();
        $title = "Private Collections";
        $today_open_time = SysConfig::getTodayOpenTime();

        $info = $this->bannerRepository->getPrivateCollection();
        $cms = $this->cmsRepository->getPrivateCollection();
        $cmsBlogs = $this->cmsRepository->getPrivateCollectionBlog();
        $caseStudies = $this->caseStudyRepository->getRandomCaseStudies(1);

        $keyContacts = $this->cmsRepository->getKeyContact('private-collections');

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'case_studies' => $caseStudies,
            'keyContacts' => $keyContacts
        ];

        return view('pages.services.private_collection', $data);
    }

    public function homeContent()
    {

        $menus = MenuHelper::getMenuService();
        $title = "Home Contents";
        $today_open_time = SysConfig::getTodayOpenTime();

        $info = $this->bannerRepository->getHomeContent();
        $cms = $this->cmsRepository->getHomeContent();
        $cmsBlogs = $this->cmsRepository->getHomeContentBlog();
        $caseStudies = $this->caseStudyRepository->getRandomCaseStudies();
        $keyContacts = $this->cmsRepository->getKeyContact('home-contents');

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'case_studies' => $caseStudies,
            'keyContacts' => $keyContacts
        ];

        return view('pages.services.home_content', $data);
    }

    public function businessSeller()
    {

        $menus = MenuHelper::getMenuService();
        $title = 'Business Sellers';
        $today_open_time = SysConfig::getTodayOpenTime();

        $info = $this->bannerRepository->getBusinessSeller();
        $cms = $this->cmsRepository->getBusinessSeller();
        $cmsBlogs = $this->cmsRepository->getBusinessSellerBlog();
        $keyContacts = $this->cmsRepository->getKeyContact('business-seller');

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
            'keyContacts' => $keyContacts
        ];

        return view('pages.services.business_sellers', $data);
    }

    public function valuations()
    {
        $menus = MenuHelper::getMenuService();
        $title = "Professional Valuations";
        $today_open_time = SysConfig::getTodayOpenTime();

        $info = $this->bannerRepository->getProfessionalValuation();
        $cms = $this->cmsRepository->getProfessionalValuation();
        $cmsBlogs = $this->cmsRepository->getProfessionalValuationBlog();
        $keyContacts = $this->cmsRepository->getKeyContact('professional-valuations');

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'keyContacts' => $keyContacts,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.services.professional_valuations', $data);
    }

    public function concierge()
    {
        $menus = MenuHelper::getMenuService();
        $title = "Estate Services";
        $today_open_time = SysConfig::getTodayOpenTime();

        $info = $this->bannerRepository->getHotlotzConcierge();
        $cms = $this->cmsRepository->getHotlotzConcierge();
        $cmsBlogs = $this->cmsRepository->getHotlotzConciergeBlog();
        $keyContacts = $this->cmsRepository->getKeyContact('hotlotz-concierge');

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
            'keyContacts' => $keyContacts
        ];

        return view('pages.services.hotlotz_concierge', $data);
    }

    public function shipping()
    {

        $menus = MenuHelper::getMenuService();
        $title = "Collection & Shipping";
        $today_open_time = SysConfig::getTodayOpenTime();

        $info = $this->bannerRepository->getCollectionShipping();
        $cms = $this->cmsRepository->getCollectionShipping();
        $cmsBlogs = $this->cmsRepository->getCollectionShippingBlog();

        $data = [
            'menus' => $menus,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'cmsBlogs' => $cmsBlogs,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.services.collection_shipping', $data);
    }

    protected function itemCollection()
    {
        $item = collect([
            [
                "icon" => "demo-icon icon-contact font_56 text_active",
                "title" => "1. Sign In",
                "description" => "Joining is free and quick"
            ],
            [
                "icon" => "demo-icon icon-document font_56 text_active",
                "title" => "2. Provide Information",
                "description" => "Dimensions, materials & history"
            ],
            [
                "icon" => "demo-icon icon-camera text_active font_40",
                "title" => "3. Submit Photograph",
                "description" => "Clear front and back colour images"
            ],
            [
                "icon" => "demo-icon icon-contact font_56 text_active",
                "title" => "4. Sign In",
                "description" => "Joining is free and quick"
            ],
            [
                "icon" => "demo-icon icon-document font_56 text_active",
                "title" => "5. Provide Information",
                "description" => "Dimensions, materials & history"
            ],
            [
                "icon" => "demo-icon icon-camera text_active font_40",
                "title" => "6. Submit Photograph",
                "description" => "Clear front and back colour images"
            ]
        ]);

        return $item;
    }

    public function caseStudy($type)
    {
        $title = "Singaporean Collection Case Study";
        $highlightTitle = "Singaporean Collection Highlights";

        if($type == 'singapore'){
            $title = "Singaporean Collection Case Study";
            $highlightTitle = "Singaporean Collection Highlights";
            $highlightItems = $this->highlightCaseStudySingapore();
            $resultRates = $this->resultRateSingapore();
        }
        if($type == 'qkl'){
            $title = "QKL Case Study";
            $highlightTitle = "QKL Highlights";
            $highlightItems = $this->highlightCaseStudyQkl();
            $resultRates = $this->resultRateQkl();
        }
        if($type == 'everton'){
            $title = "26 Everton Road Case Study";
            $highlightTitle = "26 Everton Road Highlights";
            $highlightItems = $this->highlightCaseStudyEverton();
            $resultRates = $this->resultRateEverton();
        }

        $today_open_time = SysConfig::getTodayOpenTime();

        $data = compact('title', 'highlightTitle', 'highlightItems', 'resultRates', 'today_open_time');
        return view('pages.case-study-'.$type, $data);
    }

    // HighLights items
    protected function highlightCaseStudySingapore()
    {
        $marketPlace = collect([
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_1.jpg',
                'itemTitle' => 'A PAIR OF STRAITS CHINESE PERANAKAN FAMILLE ROSE KAMCHENG',
                'priceStatus' => 'SOLD',
                'price' => "$2,000 SGD",
                'buyerLevel' => "BUYER'S PREMIUM "
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_2.jpg',
                'itemTitle' => 'A PAIR OF YELLOW GROUND FAMILLE ROSE VASES',
                'priceStatus' => 'SOLD',
                'price' => '$1,500 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_3.jpg',
                'itemTitle' => "A SET OF THREE 'DAYAZHAI' STYLE FAMILLE ROSE SMALL PLATES",
                'priceStatus' => 'SOLD',
                'price' => '$4,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_4.jpg',
                'itemTitle' => 'A NEAR PAIR OF LARGE FAMILLE ROSE AND IRON RED BALUSTER VASES',
                'priceStatus' => 'SOLD',
                'price' => '$8,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_5.jpg',
                'itemTitle' => 'A PAIR OF YELLOW GROUND FAMILLE ROSE SAUCERS',
                'priceStatus' => 'SOLD',
                'price' => '$550 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_6.jpg',
                'itemTitle' => 'A SMALL RUBY GROUND FAMILLE ROSE BOWL',
                'priceStatus' => 'SOLD',
                'price' => '$300 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_7.jpg',
                'itemTitle' => "A BLUE SILK 'EIGHT-DRAGON' ROBE, JIFU",
                'priceStatus' => 'SOLD',
                'price' => '$1,700 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_8.jpg',
                'itemTitle' => 'A BLUE MONOCHROME PORCELAIN BOTTLE VASE',
                'priceStatus' => 'SOLD',
                'price' => '$350 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_9.jpg',
                'itemTitle' => "A 'SANG DE BOEUF' GLOBULAR PORCELAIN VASE",
                'priceStatus' => 'SOLD',
                'price' => '$650 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_10.jpg',
                'itemTitle' => 'AN INCISED LONGQUAN CELADON LOTUS BOWL',
                'priceStatus' => 'SOLD',
                'price' => '$550 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_11.jpg',
                'itemTitle' => "A PAIR OF CLOISONNÉ ENAMEL DRAGON EWERS",
                'priceStatus' => 'SOLD',
                'price' => '$1,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_12.jpg',
                'itemTitle' => "A 'DAYAZHAI' FAMILLE ROSE TEA BOWL AND COVER",
                'priceStatus' => 'SOLD',
                'price' => '$5,500 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ]
        ]);

        return $marketPlace;
    }

    protected function highlightCaseStudyQkl()
    {
        $marketPlace = collect([
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_1.png',
                'itemTitle' => "A PAIR OF LARGE 'FAMILLE ROSE' PORCELAIN JARDINIERES",
                'priceStatus' => 'SOLD',
                'price' => "$5,500 SGD",
                'buyerLevel' => "BUYER'S PREMIUM "
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_2.png',
                'itemTitle' => "A 'FAMILLE ROSE' BAT AND PEACH DISH",
                'priceStatus' => 'SOLD',
                'price' => '$900 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_3.png',
                'itemTitle' => 'A PAIR OF LARGE IRON RED BALUSTER VASES',
                'priceStatus' => 'SOLD',
                'price' => '$2,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_4.png',
                'itemTitle' => "A YELLOW GLAZED RECTANGULAR 'JARDINIERE' OR NARCISSUS POT",
                'priceStatus' => 'SOLD',
                'price' => '$900 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_5.png',
                'itemTitle' => 'A LARGE PAINTED POTTERY FIGURE OF A COURT LADY WITH A DOG',
                'priceStatus' => 'SOLD',
                'price' => '$4,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_6.png',
                'itemTitle' => 'A DOUCAI TWIN HANDLED LOTUS AND BAT VASE',
                'priceStatus' => 'SOLD',
                'price' => '$10,500 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_7.png',
                'itemTitle' => 'A TALL FLAMBÉ GLAZED MEIPING VASE',
                'priceStatus' => 'SOLD',
                'price' => '$3,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_8.png',
                'itemTitle' => 'A JUN WARE CIRCULAR SHALLOW DISH',
                'priceStatus' => 'SOLD',
                'price' => '$280 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_9.png',
                'itemTitle' => 'AN AMBER GLAZED SHALLOW DISH',
                'priceStatus' => 'SOLD',
                'price' => '$300 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_10.png',
                'itemTitle' => "A FINE 'GOLDEN' CELADON TWIN FISH DISH",
                'priceStatus' => 'SOLD',
                'price' => '$3,600 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_11.png',
                'itemTitle' => "A LARGE BLUE AND WHITE PORCELAIN 'BAJIXIANG' MOON FLASK",
                'priceStatus' => 'SOLD',
                'price' => '$20,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_12.png',
                'itemTitle' => 'A PARCEL-GILT BRONZE FIGURE OF BUDDHA',
                'priceStatus' => 'SOLD',
                'price' => '$1,100 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ]
        ]);

        return $marketPlace;
    }

    protected function highlightCaseStudyEverton()
    {
        $marketPlace = collect([
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_1.png',
                'itemTitle' => "A TIBETAN 'GESSO' PAINTED ELM CABINET",
                'priceStatus' => 'SOLD',
                'price' => "$1,200 SGD",
                'buyerLevel' => "BUYER'S PREMIUM "
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_2.png',
                'itemTitle' => 'A MAHJONG GAME, IN A RED LACQUER BOX',
                'priceStatus' => 'SOLD',
                'price' => '$1,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_3.png',
                'itemTitle' => 'A WOODEN SCULPTURE OF GANESHA',
                'priceStatus' => 'SOLD',
                'price' => '$240 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_4.png',
                'itemTitle' => 'A VINTAGE BAKELITE TELEPHONE',
                'priceStatus' => 'SOLD',
                'price' => '$260 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_5.png',
                'itemTitle' => 'A SET OF THREE VIETNAMESE WOODEN LADIES',
                'priceStatus' => 'SOLD',
                'price' => '$160 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_6.png',
                'itemTitle' => 'I PRAPHAN - TWO MONKS TAKING OFFERINGS',
                'priceStatus' => 'SOLD',
                'price' => '$3,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_7.png',
                'itemTitle' => "A 'PACIFIC BIRD' PORCELAIN VASE",
                'priceStatus' => 'SOLD',
                'price' => '$550 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_8.png',
                'itemTitle' => 'A PAIR OF LARGE GLOBULAR PORCELAIN VASES',
                'priceStatus' => 'SOLD',
                'price' => '$320 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_9.png',
                'itemTitle' => 'A PAIR OF ART DECO STATUES ON MARBLE BASE',
                'priceStatus' => 'SOLD',
                'price' => '$320 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_10.png',
                'itemTitle' => 'LIULIGONGFANG - COME JOYOUS FORTUNE (GOOD LUCK)',
                'priceStatus' => 'SOLD',
                'price' => '$100 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_11.png',
                'itemTitle' => "JOHN ERDOS - A VINTAGE BRASS DESK LAMP",
                'priceStatus' => 'SOLD',
                'price' => '$180 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_12.png',
                'itemTitle' => 'AN INDONESIAN WOODEN SCULPTURE OF GARUDA BIRD',
                'priceStatus' => 'SOLD',
                'price' => '$240 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ]
        ]);

        return $marketPlace;
    }

    protected function resultRateQkl()
    {
        $rate = collect([
            [
                'name' => 'Lot Sold',
                'rate' => '223'
            ],
            [
                'name' => 'Registered Bidders',
                'rate' => '156'
            ],
            [
                'name' => 'Hammer Value (SGD)',
                'rate' => '303070'
            ],
            [
                'name' => 'Number of Bids',
                'rate' => '2326'
            ]
        ]);
        return $rate;
    }

    protected function resultRateEverton()
    {
        $rate = collect([
            [
                'name' => 'Lot Sold',
                'rate' => '90%'
            ],
            [
                'name' => 'Registered Bidders',
                'rate' => '135'
            ],
            [
                'name' => 'Hammer Value (SGD)',
                'rate' => '59750'
            ],
            [
                'name' => 'Number of Bids',
                'rate' => '314'
            ]
        ]);
        return $rate;
    }

    // Case Study Result Rates
    protected function resultRateSingapore()
    {
        $rate = collect([
            [
                'name' => 'Lot Sold',
                'rate' => '76%'
            ],
            [
                'name' => 'Registered Bidders',
                'rate' => '86'
            ],
            [
                'name' => 'Hammer Value (SGD)',
                'rate' => '91130'
            ],
            [
                'name' => 'Number of Bids',
                'rate' => '861'
            ]
        ]);
        return $rate;
    }
}