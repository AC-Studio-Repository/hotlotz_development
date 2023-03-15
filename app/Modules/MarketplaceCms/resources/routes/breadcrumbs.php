<?php

Breadcrumbs::register('marketplace_cms.marketplace_cmss.index', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Marketplace'), route('marketplace_cms.marketplace_cmss.index'));
});

Breadcrumbs::register('marketplace_cms.marketplace_cmss.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_cms.marketplace_cmss.index');
    $breadcrumbs->push(__('Edit Marketplace'), route('marketplace_cms.marketplace_cmss.editcontent'));
});