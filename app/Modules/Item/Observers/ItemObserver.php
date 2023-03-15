<?php

namespace App\Modules\Item\Observers;

use App\Modules\Item\Models\Item;
use App\Events\Xero\XeroProductEvent;

class ItemObserver
{
    /**
     * Gets changes columns
     *
     * @return string[]
     */
    public function getChangesColumns()
    {
        return [
            'purchase_cost',
            'name',
            'item_number',
            'long_description'
        ];
    }

    /**
     * Handle the post "saved" event.
     *
     * @param  \App\Modules\Item\Models\Item $item
     * @return void
     */
    public function saved(Item $item)
    {
        if (isset($item->is_hotlotz_own_stock) && $item->is_hotlotz_own_stock == 'Y' && $item->permission_to_sell == 'Y') {
            if ($item->xero_item_id == null) {
                event(new XeroProductEvent($item->id));
            } else {
                if ($item->wasChanged($this->getChangesColumns())) {
                    event(new XeroProductEvent($item->id, true));
                }
            }
        }
    }
}
