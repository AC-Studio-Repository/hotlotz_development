<?php

Breadcrumbs::register('auction.auctions.index', function ($breadcrumbs, $type = null) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__( (app('request')->input('closed') == 'yes' || $type == 'yes') ? 'Closed Auctions' : 'Current Auctions'), ($type == 'yes') ? route('auction.auctions.index', ['closed' => 'yes']) : route('auction.auctions.index'));
});

Breadcrumbs::register('auction.auctions.show', function ($breadcrumbs, $auction) {
    $breadcrumbs->parent('auction.auctions.index');
    $breadcrumbs->push(__(':name', ['name' => $auction->title]), route('auction.auctions.show', $auction));
});

Breadcrumbs::register('auction.auctions.edit', function ($breadcrumbs, $auction) {
    $breadcrumbs->parent('auction.auctions.show', $auction);
    $breadcrumbs->push(__('Edit'), route('auction.auctions.edit', $auction));
});

Breadcrumbs::register('auction.auctions.create', function ($breadcrumbs) {
    $breadcrumbs->parent('auction.auctions.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('auction.auctions.lot_list', function ($breadcrumbs, $auction) {
    $breadcrumbs->parent('auction.auctions.show', $auction);
    $breadcrumbs->push(__('Lot Reorder'), route('auction.auctions.lot_list', $auction));
});

Breadcrumbs::register('auction.auctions.show_auction', function ($breadcrumbs, $auction, $tab_name) {
    $breadcrumbs->parent('auction.auctions.index', ($auction->is_closed == 'Y') ? 'yes': null);
    $breadcrumbs->push(__(':name', ['name' => $auction->title]), route('auction.auctions.show_auction', [$auction, $tab_name]));
});