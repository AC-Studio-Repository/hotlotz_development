<?php

Breadcrumbs::register('category.categories.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Categories'), route('category.categories.index'));
});

Breadcrumbs::register('category.categories.show', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('category.categories.index');
    $breadcrumbs->push(__(':name', ['name' => $category->name]), route('category.categories.show', $category));
});

Breadcrumbs::register('category.categories.edit', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('category.categories.show', $category);
    $breadcrumbs->push(__('Edit'), route('category.categories.edit', $category));
});

Breadcrumbs::register('category.categories.create', function ($breadcrumbs) {
    $breadcrumbs->parent('category.categories.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('category.categories.store_category_property', function ($breadcrumbs) {
    $breadcrumbs->parent('category.categories.index');
    $breadcrumbs->push(__('Create Category Properties'));
});