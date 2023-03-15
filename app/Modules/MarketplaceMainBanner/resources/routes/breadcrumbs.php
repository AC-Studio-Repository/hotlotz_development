<?php

Breadcrumbs::register('marketplace_main_banner.marketplace_main_banners.index', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_home.marketplace_homes.index');
    $breadcrumbs->push(__('Home Banners'), route('marketplace_main_banner.marketplace_main_banners.index'));
});

Breadcrumbs::register('marketplace_main_banner.marketplace_main_banners.create', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_main_banner.marketplace_main_banners.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('marketplace_main_banner.marketplace_main_banners.show', function ($breadcrumbs, $marketplace_main_banner) {
    $breadcrumbs->parent('marketplace_main_banner.marketplace_main_banners.index');
    $breadcrumbs->push(__(':name', ['name' => $marketplace_main_banner->caption]), route('marketplace_main_banner.marketplace_main_banners.show', $marketplace_main_banner));
});

Breadcrumbs::register('marketplace_main_banner.marketplace_main_banners.edit', function ($breadcrumbs, $marketplace_main_banner) {
    $breadcrumbs->parent('marketplace_main_banner.marketplace_main_banners.show', $marketplace_main_banner);
    $breadcrumbs->push(__('Edit'), route('marketplace_main_banner.marketplace_main_banners.edit', $marketplace_main_banner));
});
