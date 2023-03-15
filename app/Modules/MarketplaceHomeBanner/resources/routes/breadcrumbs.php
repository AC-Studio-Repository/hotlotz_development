<?php

Breadcrumbs::register('marketplace_home_banner.marketplace_home_banners.index', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_home.marketplace_homes.index');
    $breadcrumbs->push(__('Marketplace Home'), route('marketplace_home_banner.marketplace_home_banners.index'));
});