<?php

Breadcrumbs::register('home_page_random_text.home_page_random_texts.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.index');
    $breadcrumbs->push(__('Ticker Display List'), route('home_page_random_text.home_page_random_texts.index'));
});

Breadcrumbs::register('home_page_random_text.home_page_random_texts.show', function ($breadcrumbs, $homePageRandomText) {
    $breadcrumbs->parent('home_page_random_text.home_page_random_texts.index');
    $breadcrumbs->push(__(':name', ['name' => $homePageRandomText->title]), route('home_page_random_text.home_page_random_texts.show', $homePageRandomText));
});

Breadcrumbs::register('home_page_random_text.home_page_random_texts.edit', function ($breadcrumbs, $homePageRandomText) {
    $breadcrumbs->parent('home_page_random_text.home_page_random_texts.show', $homePageRandomText);
    $breadcrumbs->push(__('Edit'), route('home_page_random_text.home_page_random_texts.edit', $homePageRandomText));
});

Breadcrumbs::register('home_page_random_text.home_page_random_texts.create', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page_random_text.home_page_random_texts.index');
    $breadcrumbs->push(__('Create'));
});