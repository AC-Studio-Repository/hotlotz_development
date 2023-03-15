<?php

Breadcrumbs::register('email_trigger.emailtriggers.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Email Trigger'), route('email_trigger.emailtriggers.index'));
});

Breadcrumbs::register('email_trigger.emailtriggers.show', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('email_trigger.emailtriggers.index');
    $breadcrumbs->push(__(':name', ['name' => $category->name]), route('email_trigger.emailtriggers.show', $category));
});

Breadcrumbs::register('email_trigger.emailtriggers.edit', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('email_trigger.emailtriggers.show', $category);
    $breadcrumbs->push(__('Edit'), route('email_trigger.emailtriggers.edit', $category));
});

Breadcrumbs::register('email_trigger.emailtriggers.create', function ($breadcrumbs) {
    $breadcrumbs->parent('email_trigger.emailtriggers.index');
    $breadcrumbs->push(__('Create'));
});