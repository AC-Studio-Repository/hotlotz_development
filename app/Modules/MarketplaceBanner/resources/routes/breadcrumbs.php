<?php

Breadcrumbs::register('marketplace_banner.marketplace_banners.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.main_banner_list');
    $breadcrumbs->push(__('Marketplace Banners'), route('marketplace_banner.marketplace_banners.index'));
});

Breadcrumbs::register('marketplace_banner.marketplace_banners.show', function ($breadcrumbs, $marketplace_banner) {
    $breadcrumbs->parent('marketplace_banner.marketplace_banners.index');
    $breadcrumbs->push(__(':name', ['name' => 'Marketplace Banner '.$marketplace_banner->id]), route('marketplace_banner.marketplace_banners.show', $marketplace_banner));
});

Breadcrumbs::register('marketplace_banner.marketplace_banners.edit', function ($breadcrumbs, $marketplace_banner) {
    $breadcrumbs->parent('marketplace_banner.marketplace_banners.show', $marketplace_banner);
    $breadcrumbs->push(__('Edit'), route('marketplace_banner.marketplace_banners.edit', $marketplace_banner));
});

Breadcrumbs::register('marketplace_banner.marketplace_banners.create', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_banner.marketplace_banners.index');
    $breadcrumbs->push(__('Create'));
});