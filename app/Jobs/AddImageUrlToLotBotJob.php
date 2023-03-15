<?php

namespace App\Jobs;

use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemImage;
use App\Helpers\NHelpers;
use DB;

class AddImageUrlToLotBotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    // public $tries = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $item_id, $lot_id;
    public function __construct($item_id, $lot_id)
    {
        $this->item_id = $item_id;
        $this->lot_id = $lot_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('gapLog')->info('Start - AddImageUrlToLotBotJob');

        try {
            $item_id = $this->item_id;
            \Log::channel('gapLog')->info('item_id : '.$item_id);

            $lot_id = $this->lot_id;
            \Log::channel('gapLog')->info('lot_id : '.$lot_id);

            $item_images = ItemImage::where('item_id', $item_id)->orderBy('id','asc')->get();
            \Log::channel('gapLog')->info('ItemImage count : '.count($item_images));

            // Add lot images
            if (count($item_images)>0) {
                foreach ($item_images as $key => $item_image) {
                    $type = pathinfo($item_image->full_path, PATHINFO_EXTENSION);
                    \Log::channel('gapLog')->info('Image Type : '. $type);

                    if ($type == 'jpg' || $type == 'jpeg') {
                        $img_data = [
                            "LotId"=> $lot_id,
                            "ImageName"=> $item_image->file_name,
                            "Url"=> $item_image->full_path,
                        ];
                        // \Log::channel('gapLog')->info('Image Data : '.print_r($img_data, true));

                        $response = Item::addImageUrlToLot($img_data);

                        if (isset($response['lot_image_id'])) {
                            \Log::channel('gapLog')->info('lot_image_id : '.$response['lot_image_id']);
                            \Log::channel('gapLog')->info('Success - AddImageUrlToLotBotJob : '.$item_image->id);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            // throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - AddImageUrlToLotBotJob');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('gapLog')->error('======= Failed - AddImageUrlToLotBotJob '. $this->item_id .'=======');
    }
}
