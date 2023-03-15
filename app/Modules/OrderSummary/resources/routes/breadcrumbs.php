<?php

Breadcrumbs::register('order_summary.order_summaries.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Order Summary'), route('order_summary.order_summaries.index'));
});

Breadcrumbs::register('order_summary.order_summaries.getTypeIndex', function ($breadcrumbs, $type) {
    if ($type == 'auction') {
        $breadcrumbs->parent('auction.auctions.index');
    } else {
        $breadcrumbs->parent('marketplace.marketplaces.index');
    }
    $breadcrumbs->push(__('Order Summary List'));
});
