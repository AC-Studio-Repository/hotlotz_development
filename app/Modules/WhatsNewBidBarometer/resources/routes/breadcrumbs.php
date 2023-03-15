<?php

Breadcrumbs::register('whats_new_bid_barometer.whats_new_bid_barometers.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.index');
    $breadcrumbs->push(__("What's New Bid Barometer"), route('whats_new_bid_barometer.whats_new_bid_barometers.index'));
});

Breadcrumbs::register('whats_new_bid_barometer.whats_new_bid_barometers.show', function ($breadcrumbs, $whats_new_bid_barometer) {
    $breadcrumbs->parent('whats_new_bid_barometer.whats_new_bid_barometers.index');
    $breadcrumbs->push(__(':name', ['name' => $whats_new_bid_barometer->title]), route('whats_new_bid_barometer.whats_new_bid_barometers.show', $whats_new_bid_barometer));
});

Breadcrumbs::register('whats_new_bid_barometer.whats_new_bid_barometers.edit', function ($breadcrumbs, $whats_new_bid_barometer) {
    $breadcrumbs->parent('whats_new_bid_barometer.whats_new_bid_barometers.show', $whats_new_bid_barometer);
    $breadcrumbs->push(__('Edit'), route('whats_new_bid_barometer.whats_new_bid_barometers.edit', $whats_new_bid_barometer));
});

Breadcrumbs::register('whats_new_bid_barometer.whats_new_bid_barometers.create', function ($breadcrumbs) {
    $breadcrumbs->parent('whats_new_bid_barometer.whats_new_bid_barometers.index');
    $breadcrumbs->push(__('Create'));
});