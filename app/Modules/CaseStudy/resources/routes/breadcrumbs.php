<?php

Breadcrumbs::register('case_study.case_study.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Case Study'), route('case_study.case_study.index'));
});

Breadcrumbs::register('case_study.case_study.detail', function ($breadcrumbs, $case_study) {
    $breadcrumbs->parent('case_study.case_study.index');
    $breadcrumbs->push(__(':name', ['name' => $case_study]), route('case_study.case_study.detail', $case_study));
});

Breadcrumbs::register('case_study.case_study.edit', function ($breadcrumbs, $case_study) {
    $breadcrumbs->parent('case_study.case_study.detail', $case_study);
    $breadcrumbs->push(__(':name', ['name' => "Edit"]), route('case_study.case_study.edit', $case_study));
});

Breadcrumbs::register('case_study.case_study.create', function ($breadcrumbs) {
    $breadcrumbs->parent('case_study.case_study.index');
    $breadcrumbs->push(__(':name', ['name' => "Add New Case Study"]), route('case_study.case_study.create'));
});
