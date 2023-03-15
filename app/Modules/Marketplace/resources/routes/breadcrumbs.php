<?php

Breadcrumbs::register('marketplace.marketplaces.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Marketplace'), route('marketplace.marketplaces.index'));
});

Breadcrumbs::register('marketplace.marketplaces.new_additions', function ($breadcrumbs) {
	$breadcrumbs->parent('home');
    $breadcrumbs->push('Marketplace');
    $breadcrumbs->push(__('New Additions'), route('marketplace.marketplaces.new_additions'));
});

Breadcrumbs::register('marketplace.marketplaces.sold_items', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Marketplace');
    $breadcrumbs->push(__('Sold'), route('marketplace.marketplaces.sold_items'));
});
