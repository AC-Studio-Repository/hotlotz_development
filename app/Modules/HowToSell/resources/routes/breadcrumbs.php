<?php

Breadcrumbs::register('how_to_sell.how_to_sells.index', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('How To Sell'), route('how_to_sell.how_to_sells.index'));
});

Breadcrumbs::register('how_to_sell.how_to_sells.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('how_to_sell.how_to_sells.index');
    $breadcrumbs->push(__('Edit How To Sell'), route('how_to_sell.how_to_sells.editcontent'));
});