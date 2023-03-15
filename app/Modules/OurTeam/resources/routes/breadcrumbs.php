<?php

Breadcrumbs::register('our_team.our_teams.showlist', function ($breadcrumbs) {
    $breadcrumbs->parent('faq_category.faqcategories.index');
    $breadcrumbs->push(__('Our Team'), route('our_team.our_teams.showlist'));
});

Breadcrumbs::register('our_team.our_teams.index', function ($breadcrumbs) {
    $breadcrumbs->parent('our_team.our_teams.showlist');
    $breadcrumbs->push(__('Team Members'), route('our_team.our_teams.index'));
});

Breadcrumbs::register('our_team.our_teams.create', function ($breadcrumbs) {
    $breadcrumbs->parent('our_team.our_teams.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('our_team.our_teams.show', function ($breadcrumbs, $our_team) {
    $breadcrumbs->parent('our_team.our_teams.index');
    $breadcrumbs->push(__(':name', ['name' => $our_team->name]), route('our_team.our_teams.show', $our_team));
});

Breadcrumbs::register('our_team.our_teams.edit', function ($breadcrumbs, $our_team) {
    $breadcrumbs->parent('our_team.our_teams.show', $our_team);
    $breadcrumbs->push(__('Edit'), route('our_team.our_teams.edit', $our_team));
});

Breadcrumbs::register('our_team.our_teams.infoIndex', function ($breadcrumbs) {
    $breadcrumbs->parent('our_team.our_teams.showlist');
    $breadcrumbs->push(__('Our Team Main'), route('our_team.our_teams.infoIndex'));
});

Breadcrumbs::register('our_team.our_teams.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('our_team.our_teams.infoIndex');
    $breadcrumbs->push(__('Edit Our Team Main'), route('our_team.our_teams.editcontent'));
});
