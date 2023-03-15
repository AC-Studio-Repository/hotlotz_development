<?php

Breadcrumbs::register('private_collections.private_collectionss.index', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Private Collections'), route('private_collections.private_collectionss.index'));
});

Breadcrumbs::register('private_collections.private_collectionss.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('private_collections.private_collectionss.index');
    $breadcrumbs->push(__('Edit Private Collections'), route('private_collections.private_collectionss.editcontent'));
});