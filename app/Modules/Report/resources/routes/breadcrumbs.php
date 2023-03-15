<?php

Breadcrumbs::register('report.reports.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Reports'), route('report.reports.index'));
});

Breadcrumbs::register('report.reports.show', function ($breadcrumbs, $report) {
    $breadcrumbs->parent('report.reports.index');
    $breadcrumbs->push(__(':name', ['name' => $report->title]), route('report.reports.show', $report));
});

Breadcrumbs::register('report.reports.edit', function ($breadcrumbs, $report) {
    $breadcrumbs->parent('report.reports.show', $report);
    $breadcrumbs->push(__('Edit'), route('report.reports.edit', $report));
});

Breadcrumbs::register('report.reports.create', function ($breadcrumbs) {
    $breadcrumbs->parent('report.reports.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('report.reports.unsold_post_auction', function ($breadcrumbs) {
    $breadcrumbs->parent('report.reports.index');
    $breadcrumbs->push(__('Post Auction Report (Unsold)'));
});

Breadcrumbs::register('report.reports.one_tree_planted_report', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('One Tree Planted Report'));
});
