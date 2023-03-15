<?php

namespace App\Modules\Item\Observers;

use App\Modules\Item\Models\ItemImage;
use App\Events\Item\CreateThumbnailEvent;

class ItemImageObserver
{
    /**
     * Gets changes columns
     *
     * @return string[]
     */
    public function getChangesColumn()
    {
        return [
            'full_path',
        ];
    }

    /**
     * Handle the post "saved" event.
     *
     * @param  \App\Modules\Item\Models\ItemImage $itemImage
     * @return void
     */
    public function saved(ItemImage $itemImage)
    {
        // if ($itemImage->wasChanged($this->getChangesColumn())) {
        //     $itemImage->thumbnail_path = null;
        //     $itemImage->save();
        //     if ($itemImage->item_id) {
                // event(new CreateThumbnailEvent($itemImage->item_id));
            // }
        // }
    }
}
