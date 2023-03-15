<?php

Breadcrumbs::register('email_log.email_logs.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Email Logs'), route('email_log.email_logs.index'));
});

Breadcrumbs::register('email_log.email_logs.show', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('email_log.email_logs.index');
    $breadcrumbs->push(__(':name', ['name' => $category]), route('email_log.email_logs.show', $category));
});
