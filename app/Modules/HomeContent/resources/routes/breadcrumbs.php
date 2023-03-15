<?php

Breadcrumbs::register('home_content.home_contents.index', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Home Content'), route('home_content.home_contents.index'));
});

Breadcrumbs::register('home_content.home_contents.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('home_content.home_contents.index');
    $breadcrumbs->push(__('Edit Home Content'), route('home_content.home_contents.editcontent'));
});