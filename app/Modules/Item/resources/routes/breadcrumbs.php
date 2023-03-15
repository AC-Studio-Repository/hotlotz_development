<?php

Breadcrumbs::register('item.items.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Items'), route('item.items.index'));
});

Breadcrumbs::register('item.items.show', function ($breadcrumbs, $item) {
    $breadcrumbs->parent('item.items.index');
    $breadcrumbs->push(__(':name', ['name' => $item->name]), route('item.items.show', $item));
});

Breadcrumbs::register('item.items.show_item', function ($breadcrumbs, $item, $tab_name) {
    $breadcrumbs->parent('item.items.index');
    $breadcrumbs->push(__(':name', ['name' => $item->name]), route('item.items.show_item', [$item, $tab_name]));
});

Breadcrumbs::register('item.items.edit', function ($breadcrumbs, $item, $tab_name) {
    $breadcrumbs->parent('item.items.show', $item);
    $breadcrumbs->push(__('Edit'), route('item.items.edit_item', [$item, $tab_name]));
});

Breadcrumbs::register('item.items.edit_item', function ($breadcrumbs, $item, $tab_name) {
    $breadcrumbs->parent('item.items.show_item', $item, $tab_name);
    $breadcrumbs->push(__('Edit'), route('item.items.edit_item', [$item, $tab_name]));
});

Breadcrumbs::register('item.items.create', function ($breadcrumbs) {
    $breadcrumbs->parent('item.items.index');
    $breadcrumbs->push(__('Create'));
});