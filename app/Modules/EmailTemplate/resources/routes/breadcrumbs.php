<?php

Breadcrumbs::register('email_template.email_templates.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Email Template'), route('email_template.email_templates.index'));
});

Breadcrumbs::register('email_template.email_templates.show', function ($breadcrumbs, $email_template) {
	// dd($email_template);
    $breadcrumbs->parent('email_template.email_templates.index');
    $breadcrumbs->push(__(':name', ['name' => $email_template->title]), route('email_template.email_templates.show', $email_template));
});

Breadcrumbs::register('email_template.email_templates.edit', function ($breadcrumbs, $email_template) {
    $breadcrumbs->parent('email_template.email_templates.show', $email_template);
    $breadcrumbs->push(__('Edit'), route('email_template.email_templates.edit', $email_template));
});

Breadcrumbs::register('email_template.email_templates.create', function ($breadcrumbs) {
    $breadcrumbs->parent('email_template.email_templates.index');
    $breadcrumbs->push(__('Create'));
});