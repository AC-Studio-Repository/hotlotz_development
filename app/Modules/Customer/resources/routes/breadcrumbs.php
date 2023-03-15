<?php

Breadcrumbs::register('customer.customers.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Clients'), route('customer.customers.index'));
});

Breadcrumbs::register('customer.customers.show', function ($breadcrumbs, $customer) {
    $breadcrumbs->parent('customer.customers.index');
    $breadcrumbs->push(__(':name', ['name' => $customer->ref_no.'_'.$customer->client_fullname]), route('customer.customers.show', $customer));
});

Breadcrumbs::register('customer.customers.show_customer', function ($breadcrumbs, $customer, $tab_name) {
    $breadcrumbs->parent('customer.customers.index');
    $breadcrumbs->push(__(':name', ['name' => $customer->ref_no.'_'.$customer->client_fullname]), route('customer.customers.show_customer', [$customer, $tab_name]));
});

Breadcrumbs::register('customer.customers.edit', function ($breadcrumbs, $customer) {
    $breadcrumbs->parent('customer.customers.show', $customer);
    $breadcrumbs->push(__('Edit'), route('customer.customers.edit', $customer));
});

Breadcrumbs::register('customer.customers.edit_customer', function ($breadcrumbs, $customer, $tab_name) {
    $breadcrumbs->parent('customer.customers.show_customer', $customer, $tab_name);
    $breadcrumbs->push(__('Edit'), route('customer.customers.edit_customer', [$customer, $tab_name]));
});

Breadcrumbs::register('customer.customers.create', function ($breadcrumbs) {
    $breadcrumbs->parent('customer.customers.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('customer.customers.splitSettlement', function ($breadcrumbs, $customer) {
    $breadcrumbs->parent('customer.customers.index');
    $breadcrumbs->push(__(':name', ['name' => $customer->ref_no.'_'.$customer->client_fullname]), route('customer.customers.show', $customer));
    $breadcrumbs->push(__('Split Invoice'));
});