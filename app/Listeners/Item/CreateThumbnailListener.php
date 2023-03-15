<?php

namespace App\Listeners\Item;

use Image;
use Illuminate\Support\Str;
use App\Modules\Item\Models\ItemImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Item\CreateThumbnailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateThumbnailListener
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
     * @param  CreateThumbnailEvent  $event
     * @return void
     */
    public function handle(CreateThumbnailEvent $event)
    {
        \Log::info('======= Start - CreateThumbnail Event '. $event->item_id.' =======');
        try {
            $item_images = ItemImage::where('item_id', $event->item_id)->get();

            foreach ($item_images as $image) {
                if ($image->full_path && $image->thumbnail_path == null) {

                     if($image->thumbnail_file_path){
                        if (Storage::exists($image->thumbnail_file_path)) {
                            Storage::delete($image->thumbnail_file_path);
                        }
                    }
                    
                    $ext = pathinfo($image->full_path, PATHINFO_EXTENSION);

                    $file = 'thumbnail/'.Str::random(20).'.'.$ext;

                    $path = 'item/'.$event->item_id.'/'.$file;

                    $img = Image::make($image->full_path);

                    $img->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $resource = $img->stream()->detach();

                    Storage::put($path, $resource);

                    $image->thumbnail_path = Storage::url($path);

                    $image->thumbnail_file_path = $path;

                    $image->save();
                }
            }

            \Log::info('======= Success - CreateThumbnail Event '. $event->item_id.' =======');
        } catch (\Exception $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            \Log::info('======= Failed - CreateThumbnail Event '. $event->item_id.' =======');
        }
        \Log::info('======= End - CreateThumbnail Event '. $event->item_id.' =======');
    }
}
