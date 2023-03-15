<?php

namespace App\Http\Controllers\Homepage;

use DB;
use Auth;
use Hash;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Helpers\SampleHelper;
use App\Http\Controllers\Controller;
use App\Repositories\BlogRepository;
use App\Repositories\ItemRepository;
use App\Repositories\BannerRepository;
use App\Repositories\TickerRepository;
use App\Repositories\AuctionRepository;
use App\Repositories\ContactRepository;
use App\Repositories\WhatsNewRepository;
use App\Modules\Customer\Models\Customer;
use App\Repositories\FavouritesRepository;
use App\Repositories\WhatWeSellRepository;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\MarketplaceRepository;
use App\Repositories\TestimonialRepository;
use App\Modules\Customer\Repositories\CustomerRepository;
use App\Modules\HomePage\Http\Repositories\HomePageTestimonialRepository;

class HomepageController extends Controller
{
    protected $homepageTestimonialRepository;
    protected $itemRepository;
    protected $bannerRepository;
    protected $auctionRepository;
    protected $testimonialRepository;
    protected $blogRepository;
    protected $marketplaceRepository;
    protected $contactRepository;
    protected $tickerRepository;
    protected $whatWeSellRepository;
    protected $favouritesRepository;
    protected $whatsNewRepository;
    protected $customerRepository;

    public function __construct(
        HomePageTestimonialRepository $homepageTestimonialRepository,
        ItemRepository $itemRepository,
        BannerRepository $bannerRepository,
        AuctionRepository $auctionRepository,
        TestimonialRepository $testimonialRepository,
        BlogRepository $blogRepository,
        MarketplaceRepository $marketplaceRepository,
        ContactRepository $contactRepository,
        TickerRepository $tickerRepository,
        WhatWeSellRepository $whatWeSellRepository,
        FavouritesRepository $favouritesRepository,
        WhatsNewRepository $whatsNewRepository,
        CustomerRepository $customerRepository
    ) {
        $this->homepageTestimonialRepository = $homepageTestimonialRepository;
        $this->itemRepository = $itemRepository;
        $this->bannerRepository = $bannerRepository;
        $this->auctionRepository = $auctionRepository;
        $this->testimonialRepository = $testimonialRepository;
        $this->blogRepository = $blogRepository;
        $this->marketplaceRepository = $marketplaceRepository;
        $this->contactRepository = $contactRepository;
        $this->tickerRepository = $tickerRepository;
        $this->whatWeSellRepository = $whatWeSellRepository;
        $this->favouritesRepository = $favouritesRepository;
        $this->whatsNewRepository = $whatsNewRepository;
        $this->customerRepository = $customerRepository;
    }


    public function index()
    {
        $title = "Home";
        $mainBanners = $this->bannerRepository->getMainBanners();
        $auctionCatalogues = $this->auctionRepository->getAuctionCatalogues();
        $comingSoonLots = [];
        if (sizeof($auctionCatalogues) < 3) {
            $comingSoonLots = $this->itemRepository->getComingSoonLots(3 - sizeof($auctionCatalogues));
        }
        $featureLots = $this->itemRepository->getFeatureLots();
        $tickerText = $this->tickerRepository->getTickerText();

        $marketplaceBanners = $this->bannerRepository->getMarketplaceBanners();
        $marketplaceHightlightTitle = "Marketplace Highlights";
        $marketplaceHighlights = $this->itemRepository->getMarketplaceHighlights();
        $whatWeSell = $this->whatWeSellRepository->getWhatWeSell();
        $whatsNew = $this->getWhatsNew();

        $todayOpenTime = SysConfig::getTodayOpenTime();
        // $WL_auction_list_link = 'https://' . config('thesaleroom.atg_tenant_id'). '/auctions/';
        $WL_auction_list_link = route('auctions.forthcoming-list');

        $data = [
            'title' => $title,
            'mainBanners' => $mainBanners,
            'auctionCatalogues' => $auctionCatalogues,
            'featureLots' => $featureLots,
            'marketplaceBanners' => $marketplaceBanners,
            'marketplaceHightlightTitle' => $marketplaceHightlightTitle,
            'marketplaceHighlights' => $marketplaceHighlights,
            'whatWeSell' => $whatWeSell,
            'whatsNew' => $whatsNew,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'today_open_time' => $todayOpenTime,
            'tickerTexts' => $tickerText,
            'WL_auction_list_link' => $WL_auction_list_link,
            'comingSoonLots' => $comingSoonLots
        ];

        return view('pages.home.main', $data);
    }

    private function getWhatsNew()
    {
        $whatsNew = collect();

        $whatsNew['articleone'] = $this->whatsNewRepository->getArtcleOneInfo();
        $whatsNew['welcome'] = $this->whatsNewRepository->getWelcomeInfo();
        $whatsNew['bidbarometer'] = $this->whatsNewRepository->getBidBarometerInfo();

        $whatsNew['blog'] = $this->blogRepository->getBlogPosts();
        $whatsNew['testimonial'] = $this->testimonialRepository->getTestimonials();

        return $whatsNew;
    }

    public function subscribe(Request $request)
    {
        $subscriber = Subscriber::where('email', $request->email)->first();
        if ($subscriber) {
        } else {
            $subscriber = new Subscriber;
            $subscriber->email = $request->email;
            $subscriber->save();
        }
        event(new \App\Events\Mailchimp\SubscriberAddOrUpdateEvent($subscriber->id));

        return redirect()->back()
            ->withInput()
            ->withError('Email subscribe successful!');
    }
}
