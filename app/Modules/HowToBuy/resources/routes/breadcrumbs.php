<?php

Breadcrumbs::register('how_to_buy.how_to_buys.index', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('How To Buy'), route('how_to_buy.how_to_buys.index'));
});

Breadcrumbs::register('how_to_buy.how_to_buys.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('how_to_buy.how_to_buys.index');
    $breadcrumbs->push(__('Edit How To Buy'), route('how_to_buy.how_to_buys.editcontent'));
});