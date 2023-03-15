<?php

Breadcrumbs::register('location_cms.location_cmss.index', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('Location'), route('location_cms.location_cmss.index'));
});

Breadcrumbs::register('location_cms.location_cmss.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('location_cms.location_cmss.index');
    $breadcrumbs->push(__('Edit Location'), route('location_cms.location_cmss.editcontent'));
});