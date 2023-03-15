<?php

Breadcrumbs::register('strategic_partner.strategic_partners.showlist', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('Strategic Partners'), route('strategic_partner.strategic_partners.showlist'));
});

Breadcrumbs::register('strategic_partner.strategic_partners.index', function ($breadcrumbs) {
    $breadcrumbs->parent('strategic_partner.strategic_partners.showlist');
    $breadcrumbs->push(__('Strategic Partner List'), route('strategic_partner.strategic_partners.index'));
});

Breadcrumbs::register('strategic_partner.strategic_partners.create', function ($breadcrumbs) {
    $breadcrumbs->parent('strategic_partner.strategic_partners.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('strategic_partner.strategic_partners.show', function ($breadcrumbs, $strategic_partner) {
    $breadcrumbs->parent('strategic_partner.strategic_partners.index');
    $breadcrumbs->push(__(':name', ['name' => $strategic_partner->title]), route('strategic_partner.strategic_partners.show', $strategic_partner));
});

Breadcrumbs::register('strategic_partner.strategic_partners.edit', function ($breadcrumbs, $strategic_partner) {
    $breadcrumbs->parent('strategic_partner.strategic_partners.show', $strategic_partner);
    $breadcrumbs->push(__('Edit'), route('strategic_partner.strategic_partners.edit', $strategic_partner));
});

Breadcrumbs::register('strategic_partner.strategic_partners.infoIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('strategic_partner.strategic_partners.showlist');
    $breadcrumbs->push(__('Strategic Partners Main'), route('strategic_partner.strategic_partners.infoIndex'));
});

Breadcrumbs::register('strategic_partner.strategic_partners.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('strategic_partner.strategic_partners.infoIndex');
    $breadcrumbs->push(__('Edit Strategic Partners Main'), route('strategic_partner.strategic_partners.editcontent'));
});