<?php

Breadcrumbs::register('item_duplicator.item_duplicator.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Item Duplicator'), route('item_duplicator.item_duplicator.index'));
});
