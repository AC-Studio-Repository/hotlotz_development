<?php

namespace App\Listeners\Item;

use App\Events\Item\SaveItemInternalPhotoToS3Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\ItemInternalPhoto;
use Illuminate\Support\Facades\Storage;

class SaveItemInternalPhotoToS3Listener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SaveItemInternalPhotoToS3Event  $event
     * @return void
     */
    public function handle(SaveItemInternalPhotoToS3Event $event)
    {
        \Log::info('Start - SaveItemInternalPhotoToS3Event');
        
        $item_id = $event->item_id;
        \Log::info('item_id : '.$item_id);

        $item_internal_photo_id = $event->item_internal_photo_id;
        \Log::info('item_internal_photo_id : '.$item_internal_photo_id);

        $item_internal_photo = ItemInternalPhoto::find($item_internal_photo_id);

        $new_path = 'item/'.$item_id;

        $fileContent = Storage::get($item_internal_photo->file_path);
        $path_parts = pathinfo($item_internal_photo->file_path);

        $new_file_path = $new_path.'/'.$path_parts['basename'];
        \Log::info('new_file_path : '.$new_file_path);
        
        Storage::put($new_file_path, $fileContent);
        $new_full_path = Storage::url($new_file_path);
        \Log::info('new_full_path : '.$new_full_path);

        Storage::delete($item_internal_photo->file_path);

        $item_internal_photo->file_path = $new_file_path;
        $item_internal_photo->full_path = $new_full_path;
        $item_internal_photo->save();

        \Log::info('End - SaveItemInternalPhotoToS3Event');
    }
}
