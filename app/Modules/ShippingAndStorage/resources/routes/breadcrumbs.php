<?php

Breadcrumbs::register('shipping_and_storage.shipping_and_storages.index', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Collection And Shipping'), route('shipping_and_storage.shipping_and_storages.index'));
});

Breadcrumbs::register('shipping_and_storage.shipping_and_storages.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('shipping_and_storage.shipping_and_storages.index');
    $breadcrumbs->push(__('Edit Collection And Shipping'), route('shipping_and_storage.shipping_and_storages.editcontent'));
});