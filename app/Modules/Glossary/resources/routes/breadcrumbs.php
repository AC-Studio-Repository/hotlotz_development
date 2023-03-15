<?php

Breadcrumbs::register('glossary.glossarys.list', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('Glossary'), route('glossary.glossarys.list'));
});

Breadcrumbs::register('glossary.glossarys.index', function ($breadcrumbs) {
    $breadcrumbs->parent('glossary.glossarys.list');
    $breadcrumbs->push(__('Glossary List'), route('glossary.glossarys.index'));
});

Breadcrumbs::register('glossary.glossarys.show', function ($breadcrumbs, $glossary) {
    $breadcrumbs->parent('glossary.glossarys.index');
    $breadcrumbs->push(__(':name', ['name' => $glossary->question]), route('glossary.glossarys.show', $glossary));
});

Breadcrumbs::register('glossary.glossarys.edit', function ($breadcrumbs, $glossary) {
    $breadcrumbs->parent('glossary.glossarys.show', $glossary);
    $breadcrumbs->push(__('Edit'), route('glossary.glossarys.edit', $glossary));
});

Breadcrumbs::register('glossary.glossarys.create', function ($breadcrumbs) {
    $breadcrumbs->parent('glossary.glossarys.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('glossary.glossarys.infopage', function ($breadcrumbs) {
    $breadcrumbs->parent('glossary.glossarys.list');
    $breadcrumbs->push(__('Glossary Main'), route('glossary.glossarys.infopage'));
});

Breadcrumbs::register('glossary.glossarys.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('glossary.glossarys.infopage');
    $breadcrumbs->push(__('Edit Glossary Main'), route('glossary.glossarys.editcontent'));
});