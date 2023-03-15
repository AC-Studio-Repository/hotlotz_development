<?php

Breadcrumbs::register('auction_cms.auction_cmss.index', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Auction'), route('auction_cms.auction_cmss.index'));
});

Breadcrumbs::register('auction_cms.auction_cmss.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('auction_cms.auction_cmss.index');
    $breadcrumbs->push(__('Edit Auction'), route('auction_cms.auction_cmss.editcontent'));
});