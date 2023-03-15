<?php

Breadcrumbs::register('hotlotz_concierge.hotlotz_concierges.index', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('Estate Services'), route('hotlotz_concierge.hotlotz_concierges.index'));
});

Breadcrumbs::register('hotlotz_concierge.hotlotz_concierges.editcontent', function ($breadcrumbs) {
    $breadcrumbs->parent('hotlotz_concierge.hotlotz_concierges.index');
    $breadcrumbs->push(__('Edit Estate Services'), route('hotlotz_concierge.hotlotz_concierges.editcontent'));
});