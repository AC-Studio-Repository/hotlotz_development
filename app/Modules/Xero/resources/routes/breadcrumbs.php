<?php

Breadcrumbs::register(
    'xero.panel', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(__('Xero Pending Invoice List'), route('xero.panel'));
    }
);

Breadcrumbs::register(
    'xero.account.services', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(__('Xero Account Services'), route('xero.account.services'));
    }
);

Breadcrumbs::register(
    'xero.account.services.edit', function ($breadcrumbs, $xeroItem) {
        $breadcrumbs->parent('xero.account.services');
        $breadcrumbs->push(__('Edit'), route('xero.account.services.edit', $xeroItem));
    }
);

Breadcrumbs::register(
    'xero.tracking.categories', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(__('Xero Tracking categories'), route('xero.tracking.categories'));
    }
);

Breadcrumbs::register(
    'xero.syncXeroInvoiceUpdate', function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(__('Sync Xero Invoice'), route('xero.syncXeroInvoiceUpdate'));
    }
);

Breadcrumbs::register(
    'xero.error',
    function ($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push(__('Xero Error List'), route('xero.error'));
    }
);
