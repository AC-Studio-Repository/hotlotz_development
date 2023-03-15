<?php

namespace App\Http\Controllers;

use App\Helpers\MenuHelper;
use App\Helpers\SampleHelper;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\ArticleRepository;
use App\Repositories\AuctionRepository;
use App\Repositories\EventRepository;
use Auth;
use DB;

class EventController extends Controller
{

    protected $eventRepository;
    protected $articleRepository;
    protected $auctionRepository;

    /**
     * Create a new controller instance.
     *
     * @param EventRepository $eventRepository
     * @param ArticleRepository $articleRepository
     * @param AuctionRepository $auctionRepository
     */
    public function __construct(
        EventRepository $eventRepository,
        ArticleRepository $articleRepository,
        AuctionRepository $auctionRepository
    )
    {
        $this->eventRepository = $eventRepository;
        $this->articleRepository = $articleRepository;
        $this->auctionRepository = $auctionRepository;
    }

    public function show($slug=""){

        $event = $this->eventRepository->getEventDetail($slug);

        $recentArticles = $this->articleRepository->getArticles();
        $bidFeatures = $this->auctionRepository->getBidOnFeatureLots();
        $bidTitle = "Bid on Featured Lots";
        $today_open_time = SysConfig::getTodayOpenTime();

        $title = $event['title'];
        $menu = MenuHelper::getMenuDiscoverEvent();

        $data = [
            'menus' => $menu,
            'banner' => "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/articles-and-events/event-1-banner.png",
            'caption' => "",
            'title' => $title,
            'mailingLists' => array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'),
            'today_open_time' => $today_open_time,
            'recentArticles' => $recentArticles,
            'bidFeatures' => $bidFeatures,
            'bidTitle' => $bidTitle,
        ];

        return view('pages.discover.event_detail', $data);
    }
}

