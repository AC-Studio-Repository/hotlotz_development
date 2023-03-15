<?php

Breadcrumbs::register('about_us.about_uss.index', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('About Us'), route('about_us.about_uss.index'));
});

Breadcrumbs::register('about_us.about_uss.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('about_us.about_uss.index');
    $breadcrumbs->push(__('Edit About Us'), route('about_us.about_uss.editcontent'));
});