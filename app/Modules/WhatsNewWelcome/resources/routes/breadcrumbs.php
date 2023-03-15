<?php

Breadcrumbs::register('whats_new_welcome.whats_new_welcomes.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home_page.home_pages.index');
    $breadcrumbs->push(__("What's New Welcome"), route('whats_new_welcome.whats_new_welcomes.index'));
});

Breadcrumbs::register('whats_new_welcome.whats_new_welcomes.show', function ($breadcrumbs, $whats_new_welcome) {
    $breadcrumbs->parent('whats_new_welcome.whats_new_welcomes.index');
    $breadcrumbs->push(__(':name', ['name' => $whats_new_welcome->title]), route('whats_new_welcome.whats_new_welcomes.show', $whats_new_welcome));
});

Breadcrumbs::register('whats_new_welcome.whats_new_welcomes.edit', function ($breadcrumbs, $whats_new_welcome) {
    $breadcrumbs->parent('whats_new_welcome.whats_new_welcomes.show', $whats_new_welcome);
    $breadcrumbs->push(__('Edit'), route('whats_new_welcome.whats_new_welcomes.edit', $whats_new_welcome));
});

Breadcrumbs::register('whats_new_welcome.whats_new_welcomes.create', function ($breadcrumbs) {
    $breadcrumbs->parent('whats_new_welcome.whats_new_welcomes.index');
    $breadcrumbs->push(__('Create'));
});