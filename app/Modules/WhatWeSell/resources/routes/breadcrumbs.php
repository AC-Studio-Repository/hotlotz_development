<?php

Breadcrumbs::register('whatwesell.whatwesells.showlist', function ($breadcrumbs) {
    $breadcrumbs->parent('professional_valuation.professional_valuations.index');
    $breadcrumbs->push(__('What We Sell'), route('whatwesell.whatwesells.showlist'));
});

Breadcrumbs::register('whatwesell.whatwesells.index', function ($breadcrumbs) {
    $breadcrumbs->parent('whatwesell.whatwesells.showlist');
    $breadcrumbs->push(__('What We Sell List'), route('whatwesell.whatwesells.index'));
});

Breadcrumbs::register('whatwesell.whatwesells.show', function ($breadcrumbs, $whatwesell) {
    $breadcrumbs->parent('whatwesell.whatwesells.index');
    $breadcrumbs->push(__(':name', ['name' => $whatwesell->title]), route('whatwesell.whatwesells.show', $whatwesell));
});

Breadcrumbs::register('whatwesell.whatwesells.edit', function ($breadcrumbs, $whatwesell) {
    $breadcrumbs->parent('whatwesell.whatwesells.show', $whatwesell);
    $breadcrumbs->push(__('Edit'), route('whatwesell.whatwesells.edit', $whatwesell));
});

Breadcrumbs::register('whatwesell.whatwesells.create', function ($breadcrumbs) {
    $breadcrumbs->parent('whatwesell.whatwesells.index');
    $breadcrumbs->push(__('Create'));
});

Breadcrumbs::register('whatwesell.whatwesells.infopage', function ($breadcrumbs) {
    $breadcrumbs->parent('whatwesell.whatwesells.showlist');
    $breadcrumbs->push(__('What We Sell Main'), route('whatwesell.whatwesells.infopage'));
});