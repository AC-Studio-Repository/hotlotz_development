<?php

Breadcrumbs::register('auction_main_page.auction_main_pages.auctionResultsIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.index');
    $breadcrumbs->push(__('Auction Results Main'), route('auction_main_page.auction_main_pages.auctionResultsIndex'));
});

Breadcrumbs::register('auction_main_page.auction_main_pages.editAuctionResultsContent', function ($breadcrumbs) {
    $breadcrumbs->parent('auction_main_page.auction_main_pages.auctionResultsIndex');
    $breadcrumbs->push(__('Edit Auction Results Main'), route('auction_main_page.auction_main_pages.editAuctionResultsContent'));
});

Breadcrumbs::register('auction_main_page.auction_main_pages.pastCataloguesIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.index');
    $breadcrumbs->push(__('Past Catalogues Main'), route('auction_main_page.auction_main_pages.pastCataloguesIndex'));
});

Breadcrumbs::register('auction_main_page.auction_main_pages.editPastCataloguesContent', function ($breadcrumbs) {
    $breadcrumbs->parent('auction_main_page.auction_main_pages.pastCataloguesIndex');
    $breadcrumbs->push(__('Edit Past Catalogues Main'), route('auction_main_page.auction_main_pages.editPastCataloguesContent'));
});