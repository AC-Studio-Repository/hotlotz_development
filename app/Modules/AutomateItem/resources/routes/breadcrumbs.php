<?php

Breadcrumbs::register('automate_item.automate_items.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Automate Items'), route('automate_item.automate_items.index'));
});
