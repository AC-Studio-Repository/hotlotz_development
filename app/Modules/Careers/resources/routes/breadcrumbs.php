<?php

Breadcrumbs::register('careers.careerss.showlist', function ($breadcrumbs) {
    $breadcrumbs->parent('content_management.termsandconditions.index');
    $breadcrumbs->push(__('Careers List'), route('careers.careerss.showlist'));
});

Breadcrumbs::register('careers.careerss.index', function ($breadcrumbs) {
    $breadcrumbs->parent('careers.careerss.showlist');
    $breadcrumbs->push(__('Careers Job List'), route('careers.careerss.index'));
});

Breadcrumbs::register('careers.careerss.create', function ($breadcrumbs) {
    $breadcrumbs->parent('careers.careerss.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('careers.careerss.show', function ($breadcrumbs, $careers) {
    $breadcrumbs->parent('careers.careerss.index');
    $breadcrumbs->push(__(':name', ['name' => $careers->title]), route('careers.careerss.show', $careers));
});

Breadcrumbs::register('careers.careerss.edit', function ($breadcrumbs, $careers) {
    $breadcrumbs->parent('careers.careerss.show', $careers);
    $breadcrumbs->push(__('Edit'), route('careers.careerss.edit', $careers));
});

Breadcrumbs::register('careers.careerss.infoIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('careers.careerss.showlist');
    $breadcrumbs->push(__('Careers Main'), route('careers.careerss.infoIndex'));
});

Breadcrumbs::register('careers.careerss.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('careers.careerss.infoIndex');
    $breadcrumbs->push(__('Edit Careers Main'), route('careers.careerss.editcontent'));
});