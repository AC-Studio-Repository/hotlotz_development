<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\GapAddImageUrlToLotEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemImage;
use App\Helpers\NHelpers;
use DB;

class GapAddImageUrlToLotListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 100;
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
     * @param  GapAddImageUrlToLotEvent  $event
     * @return void
     */
    public function handle(GapAddImageUrlToLotEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GapAddImageUrlToLotEvent');

        try {
            $item_id = $event->item_id;
            \Log::channel('gapLog')->info('item_id : '.$item_id);

            $lot_id = $event->lot_id;
            \Log::channel('gapLog')->info('lot_id : '.$lot_id);

            $item_images = ItemImage::where('item_id', $item_id)->get();

            $existing_image_ids = DB::table('lot_images')->whereNull('deleted_at')->where('item_id', $item_id)->pluck('item_image_id')->all();

            // Add lot images
            if (count($item_images)>0) {
                \Log::channel('gapLog')->info('ItemImage count : '.count($item_images));

                foreach ($item_images as $key => $item_image) {
                    $type = pathinfo($item_image->full_path, PATHINFO_EXTENSION);
                    \Log::channel('gapLog')->info('Image Type : '. $type);

                    if (($type == 'jpg' || $type == 'jpeg') && (count($existing_image_ids) <= 0 || !in_array($item_image->id, $existing_image_ids))) {
                        $img_data = [
                            "LotId"=> $lot_id,
                            "ImageName"=> $item_image->file_name,
                            "Url"=> $item_image->full_path,
                        ];
                        \Log::channel('gapLog')->info('Image Data : '.print_r($img_data, true));

                        $response = Item::addImageUrlToLot($img_data);

                        if (isset($response['lot_image_id'])) {
                            $auction_id = $event->auction_id;
                            \Log::channel('gapLog')->info('auction_id : '.$auction_id);

                            $sr_auction_id = $event->sr_auction_id;
                            \Log::channel('gapLog')->info('sr_auction_id : '.$sr_auction_id);

                            $lot_img_data = [
                                'item_image_id' => $item_image->id,
                                'item_id' => $item_id,
                                'auction_id' => $auction_id,
                                'sr_auction_id' => $sr_auction_id,
                                'lot_id' => $lot_id,
                                'lot_image_id' => $response['lot_image_id'],
                                'file_name' => $item_image->file_name,
                                'file_path' => $item_image->full_path,
                            ];
                            DB::table('lot_images')->insert($lot_img_data + NHelpers::created_updated_at_by());

                            DB::table('gap_errors')->where('reference_id', $lot_id)->where('module', 'lot_image')->where('action', 'save_lot_image')->delete();
                        }
                        if (isset($response['error'])) {
                            \Log::channel('gapLog')->info('Error : '.$response['error']);
                            $err_data = [
                                'module'=>'lot_image',
                                'reference_id'=>$lot_id,
                                'action'=>'save_lot_image',
                                'error_name'=>'Error for GAP addImageUrlToLot',
                                'error'=>$response['error'],
                                // 'description'=>'Exception when calling LotsApi->addImageUrlToLot',
                                'description'=>$item_image->full_path,
                            ];
                            DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - GapAddImageUrlToLotEvent');
    }
}
