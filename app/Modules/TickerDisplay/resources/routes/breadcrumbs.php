<?php

Breadcrumbs::register('ticker_display.ticker_displays.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.index');
    $breadcrumbs->push(__('Ticker Displays'), route('ticker_display.ticker_displays.index'));
});

Breadcrumbs::register('ticker_display.ticker_displays.show', function ($breadcrumbs, $ticker_display) {
    $breadcrumbs->parent('ticker_display.ticker_displays.index');
    $breadcrumbs->push(__(':name', ['name' => $ticker_display->title]), route('ticker_display.ticker_displays.show', $ticker_display));
});

Breadcrumbs::register('ticker_display.ticker_displays.edit', function ($breadcrumbs, $ticker_display) {
    $breadcrumbs->parent('ticker_display.ticker_displays.show', $ticker_display);
    $breadcrumbs->push(__('Edit'), route('ticker_display.ticker_displays.edit', $ticker_display));
});

Breadcrumbs::register('ticker_display.ticker_displays.create', function ($breadcrumbs) {
    $breadcrumbs->parent('ticker_display.ticker_displays.index');
    $breadcrumbs->push(__('Create'));
});