<?php

Breadcrumbs::register('sell_with_us.sell_with_uss.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Header'), route('sell_with_us.sell_with_uss.index'));
});

Breadcrumbs::register('sell_with_us.sell_with_uss.list', function ($breadcrumbs) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.index');
    $breadcrumbs->push(__('Sell With Us FAQ'), route('sell_with_us.sell_with_uss.list'));
});

Breadcrumbs::register('sell_with_us.sell_with_uss.show', function ($breadcrumbs, $sellWithUsFaq) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.list');
    $breadcrumbs->push(__(':name', ['name' => $sellWithUsFaq->title]), route('sell_with_us.sell_with_uss.show', $sellWithUsFaq));
});

Breadcrumbs::register('sell_with_us.sell_with_uss.edit', function ($breadcrumbs, $sellWithUs) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.show', $sellWithUs);
    $breadcrumbs->push(__('Edit'), route('sell_with_us.sell_with_uss.edit', $sellWithUs));
});

Breadcrumbs::register('sell_with_us.sell_with_uss.create', function ($breadcrumbs) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.list');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('sell_with_us.sell_with_uss.infopage', function ($breadcrumbs) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.index');
    $breadcrumbs->push(__('Sell With Us Main'), route('sell_with_us.sell_with_uss.infopage'));
});

Breadcrumbs::register('sell_with_us.sell_with_uss.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('sell_with_us.sell_with_uss.infopage');
    $breadcrumbs->push(__('Edit Sell With Us Main'), route('sell_with_us.sell_with_uss.editcontent'));
});
