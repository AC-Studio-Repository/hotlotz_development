<?php

namespace App\Http\Controllers\Discover;

use App\Helpers\MenuHelper;
use App\Http\Controllers\Controller;
use App\Models\GlossaryCategory;
use App\Modules\Faq\Models\Faq;
use App\Modules\FaqCategory\Models\FaqCategory;
use App\Modules\Glossary\Models\Glossary;
use App\Modules\Glossary\Models\GlossaryInfo;
use App\Modules\StrategicPartner\Models\StrategicPartner;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\ArticleRepository;
use App\Repositories\AuctionRepository;
use App\Repositories\BannerRepository;
use App\Repositories\BlogRepository;
use App\Repositories\CMSRepository;
use App\Repositories\ContactRepository;
use App\Repositories\EventRepository;
use App\Repositories\InternalAdvertRepository;
use App\Repositories\ItemRepository;
use App\Repositories\MarketplaceRepository;
use App\Repositories\TeamRepository;
use App\Repositories\TestimonialRepository;
use DB;
use Hash;

class DiscoverController extends Controller
{
    protected $itemRepository;
    protected $bannerRepository;
    protected $auctionRepository;
    protected $testimonialRepository;
    protected $blogRepository;
    protected $marketplaceRepository;
    protected $contactRepository;
    protected $eventRepository;
    protected $articleRepository;
    protected $cmsRepository;
    protected $teamRepository;
    protected $internalAdsRepository;

    public function __construct(
        ItemRepository $itemRepository,
        BannerRepository $bannerRepository,
        AuctionRepository $auctionRepository,
        TestimonialRepository $testimonialRepository,
        BlogRepository $blogRepository,
        MarketplaceRepository $marketplaceRepository,
        ContactRepository $contactRepository,
        EventRepository $eventRepository,
        ArticleRepository $articleRepository,
        CMSRepository $cmsRepository,
        TeamRepository $teamRepository,
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
        $this->eventRepository = $eventRepository;
        $this->articleRepository = $articleRepository;
        $this->cmsRepository = $cmsRepository;
        $this->teamRepository = $teamRepository;
        $this->internalAdsRepository = $internalAdsRepository;
    }

    public function aboutUs()
    {

        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "About Us";

        $info = $this->bannerRepository->getAboutus();
        $cms = $this->cmsRepository->getAboutUs();
        $cmsBlogs = $this->cmsRepository->getAboutusBlog();

        $data = [
            'menus' => $menu,
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

        return view('pages.discover.about_us', $data);
    }

    public function howtoBuy()
    {
        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "How to Buy";

        $info = $this->bannerRepository->getHowToBuy();
        $cms = $this->cmsRepository->getHowToBuy();
        $cmsBlogs = $this->cmsRepository->getHowToBuyBlog();

        $data = [
            'menus' => $menu,
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
            'download_file' => $cms['download_file'],
            'cmsBlogs' => $cmsBlogs,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.discover.how_to_buy', $data);
    }

    public function howtoSell()
    {
        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "How to Sell";

        $info = $this->bannerRepository->getHowToSell();
        $cms = $this->cmsRepository->getHowToSell();
        $cmsBlogs = $this->cmsRepository->getHowToSellBlog();

        $data = [
            'menus' => $menu,
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

        return view('pages.discover.how_to_sell', $data);
    }

    public function partners()
    {
        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "Partners";
        $strategicPartners = StrategicPartner::all();

        $info = $this->bannerRepository->getStrategicPartner();
        $cms = $this->cmsRepository->getStrategicPartner();
        $cmsBlogs = $this->cmsRepository->getStrategicPartnerBlog();

        $data = [
            'menus' => $menu,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'strategic_partners' => $strategicPartners,
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

        return view('pages.discover.partners', $data);
    }

    public function faq()
    {
        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "FAQ";
        $advertTitle = 'PROFESSIONAL VALUATIONS';
        $advertContent = 'Need help appraising your collection? Here is our valuation offers.';
        $advertUrl = 'services.valuations';
        $advertLink = 'START NOW';

        $info = $this->bannerRepository->getFAQ();
        $cms = $this->cmsRepository->getFAQ();

        $faqcategories = FaqCategory::pluck("name", "id")->all();
        $faqs = Faq::all();

        $data = [
            'menus' => $menu,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'faqs' => $faqs,
            'faqcategories' => $faqcategories,
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.discover.faq', $data);
    }

    public function location()
    {
        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "Saleroom";

        $info = $this->bannerRepository->getLocation();
        $cms = $this->cmsRepository->getLocation();

        $data = [
            'menus' => $menu,
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'direction_header' => $cms['direction_header'],
            'direction_blog' => $cms['direction_blog'],
            'saleroom_details' => $cms['saleroom_details'],
            'mon' => strtolower(SysConfig::getOpeningTime('monday')),
            'tue' => strtolower(SysConfig::getOpeningTime('tuesday')),
            'wed' => strtolower(SysConfig::getOpeningTime('wednesday')),
            'thur' => strtolower(SysConfig::getOpeningTime('thursday')),
            'fri' => strtolower(SysConfig::getOpeningTime('friday')),
            'sat' => strtolower(SysConfig::getOpeningTime('saturday')),
            'sun' => strtolower(SysConfig::getOpeningTime('sunday')),
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.discover.location', $data);
    }

    public function team()
    {
        $menu = MenuHelper::getMenuDiscover();
        $title = "Team";
        $buyTitle = "Marketplace Highlights";

        $marketPlaces = $this->itemRepository->getMarketplaceItems();
        $today_open_time = SysConfig::getTodayOpenTime();
        $mainSubsTitle = 'Stay Up to Date with Hotlotz';

        $info = $this->bannerRepository->getOurTeam();
        $cms = $this->cmsRepository->getOurTeam();
        $teams = $this->teamRepository->getOurTeamData();

        $marketplaceHightlightTitle = "What to Buy Now";
        $marketplaceHighlights = $this->itemRepository->getMarketplaceHighlights();

        $data = [
            'menus' => $menu,
            'title' => $title,
            'marketPlaces' => $marketPlaces,
            'buyTitle' => $buyTitle,
            'today_open_time' => $today_open_time,
            'mainSubsTitle' => $mainSubsTitle,
            'banner' => $info['image'],
            'caption' => $info['caption'],
            'title_header' => $cms['title_header'],
            'title_blog' => $cms['title_blog'],
            'teams' => $teams,
            'marketplaceHightlightTitle' => $marketplaceHightlightTitle,
            'marketplaceHighlights' => $marketplaceHighlights
        ];

        return view('pages.discover.team', $data);
    }

    public function glossary()
    {
        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "Glossary";

        $glossarydata = Glossary::all();
        $glossaryfaqs = $glossarydata->sortBy('question');

        // $glossaryfaqs = Glossary::orderBy('question')->get();
        $glossarycategories = GlossaryCategory::pluck("name", "id")->all();
        $glossary_data = GlossaryInfo::all();
        $glossary = [];

        if (!$glossary_data->isEmpty()){
            $glossary = $glossary_data->first();
        }

        $data = [
            'menus' => $menu,
            'banner' => $glossary->banner_image,
            'caption' => $glossary->caption,
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'glossary_data' => $glossary_data,
            'glossary' => $glossary,
            'glossarycategories' => $glossarycategories,
            'glossaryfaqs' => $glossaryfaqs,
            'ads' => $this->internalAdsRepository->getRandomInternalAds(2),
        ];

        return view('pages.discover.glossary', $data);
    }

    public function articlesAndEvents()
    {
        $menu = MenuHelper::getMenuDiscover();
        $today_open_time = SysConfig::getTodayOpenTime();
        $title = "Media Coverage";
        $description = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis voluptas, ea eaque totam quaerat, veritatis mollitia illo culpa praesentium laborum pariatur deleniti dolorem eligendi. Neque molestiasreprehenderit earum quo perferendis! Neque molestias reprehenderit earum quo perferendis!";

        $data = [
            'menus' => $menu,
            'banner' => "/ecommerce/images/Discover/articles-events/Articles_&_Events_banner.jpg",
            'caption' => "",
            'title' => $title,
            'today_open_time' => $today_open_time,
            'next_auction' => $this->auctionRepository->getNextAuction(),
            'marketplaceHightlightTitle' => "Marketplace Highlights",
            'marketplaceHighlights' => $this->itemRepository->getMarketplaceHighlights(),
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'description' => $description,
            // 'events' => $this->eventRepository->getEvents(),
            // 'articleCategories' => $this->articleRepository->getArticleCategories(),
            // 'articleContents' => $this->articleRepository->getArticles(),
            'blog_posts' => $this->eventRepository->getEvents(),
            'article_data' => $this->articleRepository->getArticles(),
        ];

        // dd($data);

        return view('pages.discover.articles_events', $data);
    }

}
