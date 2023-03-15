<?php

namespace App\Listeners\Item;

use App\Events\Item\SaveItemVideoToS3Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\ItemVideo;
use Illuminate\Support\Facades\Storage;

class SaveItemVideoToS3Listener
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
     * @param  SaveItemVideoToS3Event  $event
     * @return void
     */
    public function handle(SaveItemVideoToS3Event $event)
    {
        \Log::info('Start - SaveItemVideoToS3Event');
        
        $item_id = $event->item_id;
        \Log::info('item_id : '.$item_id);

        $item_video_id = $event->item_video_id;
        \Log::info('item_video_id : '.$item_video_id);

        $item_video = ItemVideo::find($item_video_id);

        $new_path = 'item/'.$item_id;

        $fileContent = Storage::get($item_video->file_path);
        $path_parts = pathinfo($item_video->file_path);

        $new_file_path = $new_path.'/'.$path_parts['basename'];
        \Log::info('new_file_path : '.$new_file_path);
        
        Storage::put($new_file_path, $fileContent);
        $new_full_path = Storage::url($new_file_path);
        \Log::info('new_full_path : '.$new_full_path);

        Storage::delete($item_video->file_path);

        $item_video->file_path = $new_file_path;
        $item_video->full_path = $new_full_path;
        $item_video->save();

        \Log::info('End - SaveItemVideoToS3Event');
    }
}
