<?php

Breadcrumbs::register('support.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Support'), route('support.index'));
});
