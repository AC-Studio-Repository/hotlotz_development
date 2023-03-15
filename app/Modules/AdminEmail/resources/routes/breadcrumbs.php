<?php

Breadcrumbs::register('admin_email.admin_emails.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Admin Email'), route('admin_email.admin_emails.index'));
});

Breadcrumbs::register('admin_email.admin_emails.show', function ($breadcrumbs, $admin_email) {
	// dd($admin_email);
    $breadcrumbs->parent('admin_email.admin_emails.index');
    $breadcrumbs->push(__(':name', ['name' => $admin_email->title]), route('admin_email.admin_emails.show', $admin_email));
});

Breadcrumbs::register('admin_email.admin_emails.edit', function ($breadcrumbs, $admin_email) {
    $breadcrumbs->parent('admin_email.admin_emails.show', $admin_email);
    $breadcrumbs->push(__('Edit'), route('admin_email.admin_emails.edit', $admin_email));
});

Breadcrumbs::register('admin_email.admin_emails.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin_email.admin_emails.index');
    $breadcrumbs->push(__('Create'));
});