<?php

Breadcrumbs::register('business_seller.business_sellers.index', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Business Seller'), route('business_seller.business_sellers.index'));
});

Breadcrumbs::register('business_seller.business_sellers.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('business_seller.business_sellers.index');
    $breadcrumbs->push(__('Edit Business Seller'), route('business_seller.business_sellers.editcontent'));
});