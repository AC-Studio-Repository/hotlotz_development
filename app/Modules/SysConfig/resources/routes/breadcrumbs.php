<?php

Breadcrumbs::register('sys_config.sys_configs.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('System Configuration'), route('sys_config.sys_configs.index'));
});

Breadcrumbs::register('sys_config.sys_configs.show', function ($breadcrumbs, $sys_config) {
	// dd($sys_config);
    $breadcrumbs->parent('sys_config.sys_configs.index');
    $breadcrumbs->push(__(':name', ['name' => $sys_config->title]), route('sys_config.sys_configs.show', $sys_config));
});

Breadcrumbs::register('sys_config.sys_configs.edit', function ($breadcrumbs, $sys_config) {
    $breadcrumbs->parent('sys_config.sys_configs.show', $sys_config);
    $breadcrumbs->push(__('Edit'), route('sys_config.sys_configs.edit', $sys_config));
});

Breadcrumbs::register('sys_config.sys_configs.create', function ($breadcrumbs) {
    $breadcrumbs->parent('sys_config.sys_configs.index');
    $breadcrumbs->push(__('Create'));
});