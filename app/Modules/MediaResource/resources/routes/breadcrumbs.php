<?php

Breadcrumbs::register('media_resource.media_resources.showlist', function ($breadcrumbs) {
    $breadcrumbs->parent('content_management.termsandconditions.index');
    $breadcrumbs->push(__('Media Resource List'), route('media_resource.media_resources.showlist'));
});

Breadcrumbs::register('media_resource.media_resources.index', function ($breadcrumbs) {
    $breadcrumbs->parent('media_resource.media_resources.showlist');
    $breadcrumbs->push(__('Media Resource Press Release List'), route('media_resource.media_resources.index'));
});

Breadcrumbs::register('media_resource.media_resources.create', function ($breadcrumbs) {
    $breadcrumbs->parent('media_resource.media_resources.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('media_resource.media_resources.show', function ($breadcrumbs, $media_resource) {
    $breadcrumbs->parent('media_resource.media_resources.index');
    $breadcrumbs->push(__(':name', ['name' => $media_resource->title]), route('media_resource.media_resources.show', $media_resource));
});

Breadcrumbs::register('media_resource.media_resources.edit', function ($breadcrumbs, $media_resource) {
    $breadcrumbs->parent('media_resource.media_resources.show', $media_resource);
    $breadcrumbs->push(__('Edit'), route('media_resource.media_resources.edit', $media_resource));
});

Breadcrumbs::register('media_resource.media_resources.infoIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('media_resource.media_resources.showlist');
    $breadcrumbs->push(__('Media Resource Main'), route('media_resource.media_resources.infoIndex'));
});

Breadcrumbs::register('media_resource.media_resources.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('media_resource.media_resources.infoIndex');
    $breadcrumbs->push(__('Edit Media Resource Main'), route('media_resource.media_resources.editcontent'));
});