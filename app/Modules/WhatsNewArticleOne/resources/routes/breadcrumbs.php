<?php

Breadcrumbs::register('whats_new_article_one.whats_new_article_ones.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.index');
    $breadcrumbs->push(__("What's New Article One"), route('whats_new_article_one.whats_new_article_ones.index'));
});

Breadcrumbs::register('whats_new_article_one.whats_new_article_ones.show', function ($breadcrumbs, $whats_new_article_one) {
    $breadcrumbs->parent('whats_new_article_one.whats_new_article_ones.index');
    $breadcrumbs->push(__(':name', ['name' => $whats_new_article_one->title]), route('whats_new_article_one.whats_new_article_ones.show', $whats_new_article_one));
});

Breadcrumbs::register('whats_new_article_one.whats_new_article_ones.edit', function ($breadcrumbs, $whats_new_article_one) {
    $breadcrumbs->parent('whats_new_article_one.whats_new_article_ones.show', $whats_new_article_one);
    $breadcrumbs->push(__('Edit'), route('whats_new_article_one.whats_new_article_ones.edit', $whats_new_article_one));
});

Breadcrumbs::register('whats_new_article_one.whats_new_article_ones.create', function ($breadcrumbs) {
    $breadcrumbs->parent('whats_new_article_one.whats_new_article_ones.index');
    $breadcrumbs->push(__('Create'));
});