<?php

Breadcrumbs::register('faq_category.faqcategories.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Discover'), route('faq_category.faqcategories.index'));
});

Breadcrumbs::register('faq_category.faqcategories.showlist', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('FAQS'), route('faq_category.faqcategories.showlist'));
});

Breadcrumbs::register('faq_category.faqcategories.faqCategoryList', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.showlist');
    $breadcrumbs->push(__('FAQ Category List'), route('faq_category.faqcategories.faqCategoryList'));
});

Breadcrumbs::register('faq_category.faqcategories.show', function ($breadcrumbs, $faqcategory) {
    $breadcrumbs->parent('faq_category.faqcategories.faqCategoryList');
    $breadcrumbs->push(__(':name', ['name' => $faqcategory->title]), route('faq_category.faqcategories.show', $faqcategory));
});

Breadcrumbs::register('faq_category.faqcategories.edit', function ($breadcrumbs, $faqcategory) {
    $breadcrumbs->parent('faq_category.faqcategories.show', $faqcategory);
    $breadcrumbs->push(__('Edit'), route('faq_category.faqcategories.edit', $faqcategory));
});

Breadcrumbs::register('faq_category.faqcategories.create', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.faqCategoryList');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('faq_category.faqcategories.bloglist', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('Media Coverage'), route('faq_category.faqcategories.bloglist'));
});