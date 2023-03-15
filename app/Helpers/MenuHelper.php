<?php

namespace App\Helpers;

class MenuHelper
{

    public static function getMenuAuction() {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Auctions',
                'url' => ''
            ]
        ]);
    }

    public static function getMenuMarketplace() {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Marketplace',
                'url' => route('marketplace.index')
            ]
        ]);
    }

    public static function getMenuInvoices() {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Invoices',
                'url' => ''
            ]
        ]);
    }

    public static function getMenuService() {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Services',
                'url' => ''
            ]
        ]);
    }

    public static function getMenuWhatWeSell() {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Services',
                'url' => ''
            ],
            [
                'name' => 'What We Sell',
                'url' => route('services.what-we-sell')
            ]
        ]);
    }

    public static function getMenuDiscover()
    {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Discover',
                'url' => ''
            ]
        ]);
    }

    public static function getMenuDiscoverEvent()
    {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Discover',
                'url' => ''
            ],
            [
                'name' => 'Media Coverage & Events',
                'url' => route('discover.articles-events')
            ]
        ]);
    }

    public static function getMenuFooter() {
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ]
        ]);
    }

    public static function getSearchMenu(){
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Search',
                'url' => '#'
            ]
        ]);
    }

    public static function getCaseStudyMenu(){
        return collect([
            [
                'name' => 'Home',
                'url' => route('home')
            ],
            [
                'name' => 'Services',
                'url' => ''
            ],
            [
                'name' => 'Private Collections',
                'url' => route('services.private-collection')
            ]
        ]);
    }
}
