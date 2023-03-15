<?php

Breadcrumbs::register('marketing.marketings.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Marketing'), route('marketing.marketings.index'));
});

Breadcrumbs::register('marketing.marketings.show', function ($breadcrumbs, $marketing) {
    $breadcrumbs->parent('marketing.marketings.index');
    $breadcrumbs->push(__(':name', ['name' => $marketing->title]), route('marketing.marketings.show', $marketing));
});

Breadcrumbs::register('marketing.marketings.edit', function ($breadcrumbs, $marketing) {
    $breadcrumbs->parent('marketing.marketings.show', $marketing);
    $breadcrumbs->push(__('Edit'), route('marketing.marketings.edit', $marketing));
});

Breadcrumbs::register('marketing.marketings.create', function ($breadcrumbs) {
    $breadcrumbs->parent('marketing.marketings.index');
    $breadcrumbs->push(__('Create'));
});