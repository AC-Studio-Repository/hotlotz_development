<?php

Breadcrumbs::register('professional_valuation.professional_valuations.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Services'), route('professional_valuation.professional_valuations.index'));
});

Breadcrumbs::register('professional_valuation.professional_valuations.contentIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Professional Valuations'), route('professional_valuation.professional_valuations.contentIndex'));
});

Breadcrumbs::register('professional_valuation.professional_valuations.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.contentIndex');
    $breadcrumbs->push(__('Edit Professional Valuations'), route('professional_valuation.professional_valuations.editcontent'));
});


