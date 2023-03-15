<?php

Breadcrumbs::register('main_banner.main_banners.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.main_banner_list');
    $breadcrumbs->push(__('Main Banners'), route('main_banner.main_banners.index'));
});

Breadcrumbs::register('main_banner.main_banners.show', function ($breadcrumbs, $main_banner) {
    $breadcrumbs->parent('main_banner.main_banners.index');
    $breadcrumbs->push(__(':name', ['name' => $main_banner->main_title]), route('main_banner.main_banners.show', $main_banner));
});

Breadcrumbs::register('main_banner.main_banners.edit', function ($breadcrumbs, $main_banner) {
    $breadcrumbs->parent('main_banner.main_banners.show', $main_banner);
    $breadcrumbs->push(__('Edit'), route('main_banner.main_banners.edit', $main_banner));
});

Breadcrumbs::register('main_banner.main_banners.create', function ($breadcrumbs) {
    $breadcrumbs->parent('main_banner.main_banners.index');
    $breadcrumbs->push(__('Create'));
});