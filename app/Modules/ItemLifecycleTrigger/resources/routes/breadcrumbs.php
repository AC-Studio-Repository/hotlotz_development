<?php

Breadcrumbs::register('item_lifecycle_trigger.itemlifecycletriggers.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Item Lifecycle Trigger'), route('item_lifecycle_trigger.itemlifecycletriggers.index'));
});

Breadcrumbs::register('item_lifecycle_trigger.itemlifecycletriggers.show', function ($breadcrumbs, $itemlifecycletrigger) {
    $breadcrumbs->parent('item_lifecycle_trigger.itemlifecycletriggers.index');
    $breadcrumbs->push(__(':name', ['name' => $itemlifecycletrigger->name]), route('item_lifecycle_trigger.itemlifecycletriggers.show', $itemlifecycletrigger));
});

Breadcrumbs::register('item_lifecycle_trigger.itemlifecycletriggers.edit', function ($breadcrumbs, $itemlifecycletrigger) {
    $breadcrumbs->parent('item_lifecycle_trigger.itemlifecycletriggers.show', $itemlifecycletrigger);
    $breadcrumbs->push(__('Edit'), route('item_lifecycle_trigger.itemlifecycletriggers.edit', $itemlifecycletrigger));
});

Breadcrumbs::register('item_lifecycle_trigger.itemlifecycletriggers.create', function ($breadcrumbs) {
    $breadcrumbs->parent('item_lifecycle_trigger.itemlifecycletriggers.index');
    $breadcrumbs->push(__('Create'));
});