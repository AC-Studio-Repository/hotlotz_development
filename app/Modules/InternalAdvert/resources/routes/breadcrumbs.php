<?php

Breadcrumbs::register('internal_advert.internal_advert.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Internal Advert'), route('internal_advert.internal_advert.index'));
});

Breadcrumbs::register('internal_advert.internal_advert.detail', function ($breadcrumbs, $internal_advert) {
    $breadcrumbs->parent('internal_advert.internal_advert.index');
    $breadcrumbs->push(__(':name', ['name' => $internal_advert]), route('internal_advert.internal_advert.detail', $internal_advert));
});

Breadcrumbs::register('internal_advert.internal_advert.edit', function ($breadcrumbs, $internal_advert) {
    $breadcrumbs->parent('internal_advert.internal_advert.detail', $internal_advert);
    $breadcrumbs->push(__(':name', ['name' => "Edit"]), route('internal_advert.internal_advert.edit', $internal_advert));
});

Breadcrumbs::register('internal_advert.internal_advert.create', function ($breadcrumbs) {
    $breadcrumbs->parent('internal_advert.internal_advert.index');
    $breadcrumbs->push(__(':name', ['name' => "Add New Internal Advert"]), route('internal_advert.internal_advert.create'));
});
