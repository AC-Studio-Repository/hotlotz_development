<?php

Breadcrumbs::register('content_management.termsandconditions.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Footer Menu'), route('content_management.termsandconditions.index'));
});

Breadcrumbs::register('content_management.termsandconditions.displayContentTandC', function ($breadcrumbs) {
    $breadcrumbs->parent('content_management.termsandconditions.index');
    $breadcrumbs->push(__('Terms and Conditions'), route('content_management.termsandconditions.displayContentTandC'));
});

Breadcrumbs::register('content_management.termsandconditions.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('content_management.termsandconditions.displayContentTandC');
    $breadcrumbs->push(__('Edit Terms and Conditions'), route('content_management.termsandconditions.editcontent'));
});


