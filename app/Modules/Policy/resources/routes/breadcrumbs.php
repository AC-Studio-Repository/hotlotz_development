<?php

Breadcrumbs::register('policy.policies.index', function ($breadcrumbs) {
    $breadcrumbs->parent('content_management.termsandconditions.index');
    $breadcrumbs->push(__('Policy List'), route('policy.policies.index'));
});

Breadcrumbs::register('policy.policies.show', function ($breadcrumbs, $policy) {
    $breadcrumbs->parent('policy.policies.index');
    $breadcrumbs->push(__(':name', ['name' => $policy->title]), route('policy.policies.show', $policy));
});

Breadcrumbs::register('policy.policies.edit', function ($breadcrumbs, $policy) {
    $breadcrumbs->parent('policy.policies.show', $policy);
    $breadcrumbs->push(__('Edit'), route('policy.policies.edit', $policy));
});

Breadcrumbs::register('policy.policies.create', function ($breadcrumbs) {
    $breadcrumbs->parent('policy.policies.index');
    $breadcrumbs->push(__('Create'));
});