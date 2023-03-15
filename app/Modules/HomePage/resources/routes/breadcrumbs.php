<?php

Breadcrumbs::register('home_page.home_pages.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Home Page'), route('home_page.home_pages.index'));
});

Breadcrumbs::register('home_page.home_pages.showtestimonial', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.index');
    $breadcrumbs->push(__('Home Page Testimonial'), route('home_page.home_pages.showtestimonial'));
});

Breadcrumbs::register('home_page.home_pages.main_banner_list', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.index');
    $breadcrumbs->push(__('Banners'), route('home_page.home_pages.index'));
});

Breadcrumbs::register('home_page.home_pages.main_banner_index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.main_banner_list');
    $breadcrumbs->push(__('Main Banner'), route('home_page.home_pages.main_banner_index'));
});

Breadcrumbs::register('home_page.home_pages.marketplace_banner_index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.main_banner_list');
    $breadcrumbs->push(__('Marketplace Banner'), route('home_page.home_pages.marketplace_banner_index'));
});