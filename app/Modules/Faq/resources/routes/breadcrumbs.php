<?php

Breadcrumbs::register('faq.faqs.index', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.showlist');
    $breadcrumbs->push(__('FAQ List'), route('faq.faqs.index'));
});

Breadcrumbs::register('faq.faqs.show', function ($breadcrumbs, $faq) {
    $breadcrumbs->parent('faq.faqs.index');
    $breadcrumbs->push(__(':name', ['name' => $faq->title]), route('faq.faqs.show', $faq));
});

Breadcrumbs::register('faq.faqs.edit', function ($breadcrumbs, $faq) {
    $breadcrumbs->parent('faq.faqs.show', $faq);
    $breadcrumbs->push(__('Edit'), route('faq.faqs.edit', $faq));
});

Breadcrumbs::register('faq.faqs.create', function ($breadcrumbs) {
    $breadcrumbs->parent('faq.faqs.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('faq.faqs.infoIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.showlist');
    $breadcrumbs->push(__('FAQ Main'), route('faq.faqs.infoIndex'));
});

Breadcrumbs::register('faq.faqs.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('faq.faqs.infoIndex');
    $breadcrumbs->push(__('Edit FAQ Main'), route('faq.faqs.editcontent'));
});