<?php

Breadcrumbs::register('what_we_sell.what_we_sells.showlist', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('What We Sell'), route('what_we_sell.what_we_sells.showlist'));
});

Breadcrumbs::register('what_we_sell.what_we_sells.index', function ($breadcrumbs) {
    $breadcrumbs->parent('what_we_sell.what_we_sells.showlist');
    $breadcrumbs->push(__('What We Sell List'), route('what_we_sell.what_we_sells.index'));
});

Breadcrumbs::register('what_we_sell.what_we_sells.show', function ($breadcrumbs, $what_we_sell) {
    $breadcrumbs->parent('what_we_sell.what_we_sells.index');
    $breadcrumbs->push(__(':name', ['name' => $what_we_sell->title]), route('what_we_sell.what_we_sells.show', $what_we_sell));
});

Breadcrumbs::register('what_we_sell.what_we_sells.edit', function ($breadcrumbs, $what_we_sell) {
    $breadcrumbs->parent('what_we_sell.what_we_sells.show', $what_we_sell);
    $breadcrumbs->push(__('Edit'), route('what_we_sell.what_we_sells.edit', $what_we_sell));
});

Breadcrumbs::register('what_we_sell.what_we_sells.create', function ($breadcrumbs) {
    $breadcrumbs->parent('what_we_sell.what_we_sells.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('what_we_sell.what_we_sells.highlight_list', function ($breadcrumbs, $what_we_sell) {
    $breadcrumbs->parent('what_we_sell.what_we_sells.show', $what_we_sell);
    $breadcrumbs->push(__('Highlight List'), route('what_we_sell.what_we_sells.highlight_list', $what_we_sell));
});