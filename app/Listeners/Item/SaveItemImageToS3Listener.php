<?php

namespace App\Listeners\Item;

use App\Modules\Item\Models\ItemImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Item\CreateThumbnailEvent;
use App\Events\Item\SaveItemImageToS3Event;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveItemImageToS3Listener
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
     * @param  SaveItemImageToS3Event  $event
     * @return void
     */
    public function handle(SaveItemImageToS3Event $event)
    {
        \Log::info('Start - SaveItemImageToS3Event');

        $item_id = $event->item_id;
        \Log::info('item_id : '.$item_id);

        $item_image_id = $event->item_image_id;
        \Log::info('item_image_id : '.$item_image_id);

        $image_reorder = $event->image_reorder;
        \Log::info('image_reorder : '.$image_reorder);

        $item_image = ItemImage::find($item_image_id);

        $new_path = 'item/'.$item_id;

        $fileContent = Storage::get($item_image->file_path);
        $path_parts = pathinfo($item_image->file_path);
        // \Log::info('path_parts : '.print_r($path_parts, true));

        // $name = (string) \Str::uuid();
        // $extension = $path_parts['extension'];
        $new_file_path = $new_path.'/'.$path_parts['basename'];
        \Log::info('new_file_path : '.$new_file_path);

        Storage::put($new_file_path, $fileContent);
        $new_full_path = Storage::url($new_file_path);
        \Log::info('new_full_path : '.$new_full_path);

        Storage::delete($item_image->file_path);

        if($image_reorder != 'edit'){
            // $item_image->file_name = $name .'.'. $extension;
            $item_image->file_path = $new_file_path;
            $item_image->full_path = $new_full_path;
            $item_image->thumbnail_path = null;
            $item_image->save();

            event(new CreateThumbnailEvent($item_id));
        }
        if($image_reorder != null && $image_reorder == 'edit'){
            $insert_item_imgs = [
                'item_id' => $item_id,
                'file_name' => $item_image->file_name,
                'file_path' => $new_file_path,
                'full_path' => $new_full_path,
            ];
            ItemImage::create($insert_item_imgs);
            event(new CreateThumbnailEvent($item_id));
            $item_image->forceDelete();
        }

        \Log::info('End - SaveItemImageToS3Event');
    }
}
