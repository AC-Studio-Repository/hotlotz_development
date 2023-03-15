<?php

Breadcrumbs::register('marketplace_home.marketplace_homes.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Marketplace'), route('marketplace_home.marketplace_homes.index'));
});

Breadcrumbs::register('marketplace_home.marketplace_homes.substainable_banner_index', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_home.marketplace_homes.index');
    $breadcrumbs->push(__('Sustainable Banner'), route('marketplace_home.marketplace_homes.substainable_banner_index'));
});

Breadcrumbs::register('marketplace_home.marketplace_homes.collaboration_banner_index', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_home.marketplace_homes.index');
    $breadcrumbs->push(__('Collaboration Banner'), route('marketplace_home.marketplace_homes.collaboration_banner_index'));
});

Breadcrumbs::register('marketplace_home.marketplace_homes.collaboration_list', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_home.marketplace_homes.index');
    $breadcrumbs->push(__('Collaboration Banner'), route('marketplace_home.marketplace_homes.collaboration_list'));
});

Breadcrumbs::register('marketplace_home.marketplace_homes.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_home.marketplace_homes.index');
    $breadcrumbs->push(__('Edit Collaboration Page'), route('marketplace_home.marketplace_homes.editcontent'));
});

Breadcrumbs::register('marketplace_home.marketplace_homes.itemDetailPolicyCms', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_home.marketplace_homes.index');
    $breadcrumbs->push(__('Collection & Shipping, One Tree Planted, Sale Policy'), route('marketplace_home.marketplace_homes.itemDetailPolicyCms'));
});
