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
use App\Modules\Auction\Models\Auction;
use App\Helpers\NHelpers;
use DB;

class AddImageUrlToLotJob implements ShouldQueue
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
    public $item_id;
    public $lot_id;
    public $auction_id;
    public $sr_auction_id;
    public function __construct($item_id, $lot_id, $auction_id, $sr_auction_id)
    {
        $this->item_id = $item_id;
        $this->lot_id = $lot_id;
        $this->auction_id = $auction_id;
        $this->sr_auction_id = $sr_auction_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('gapLog')->info('Start - AddImageUrlToLotJob');

        try {
            $item_id = $this->item_id;
            \Log::channel('gapLog')->info('item_id : '.$item_id);

            $lot_id = $this->lot_id;
            \Log::channel('gapLog')->info('lot_id : '.$lot_id);

            $auction_id = $this->auction_id;
            \Log::channel('gapLog')->info('auction_id : '.$auction_id);

            $auction = Auction::find($auction_id);
            if($auction && $auction != null) {
                \Log::channel('gapLog')->info('sr_auction_id : '.$auction->sr_auction_id);

                $item_images = ItemImage::where('item_id', $item_id)->orderBy('id','asc')->get();

                $existing_image_ids = DB::table('lot_images')->whereNull('deleted_at')->where('item_id', $item_id)->where('auction_id', $auction_id)->pluck('item_image_id')->all();

                // Add lot images
                if (count($item_images)>0) {
                    \Log::channel('gapLog')->info('ItemImage count : '.count($item_images));

                    foreach ($item_images as $key => $item_image) {
                        $type = pathinfo($item_image->full_path, PATHINFO_EXTENSION);
                        \Log::channel('gapLog')->info('Image Type : '. $type);

                        if (($type == 'jpg' || $type == 'jpeg') && (count($existing_image_ids) <= 0 || !in_array($item_image->id, $existing_image_ids))) {
                            $img_data = [
                                "LotId"=> $lot_id,
                                "ImageName"=> ($key+1).".".$type,//$item_image->file_name,
                                "Url"=> $item_image->full_path,
                            ];
                            \Log::channel('gapLog')->info('Image Data : '.print_r($img_data, true));

                            $response = Item::addImageUrlToLot($img_data);

                            if (isset($response['lot_image_id'])) {

                                $lot_img_data = [
                                    'item_image_id' => $item_image->id,
                                    'item_id' => $item_id,
                                    'auction_id' => $auction_id,
                                    'sr_auction_id' => $auction->sr_auction_id,
                                    'lot_id' => $lot_id,
                                    'lot_image_id' => $response['lot_image_id'],
                                    'file_name' => $item_image->file_name,
                                    'file_path' => $item_image->full_path,
                                ];
                                DB::table('lot_images')->insert($lot_img_data + NHelpers::created_updated_at_by());

                                DB::table('gap_errors')->where('reference_id', $lot_id)->where('module', 'lot_image')->where('action', 'save_lot_image')->delete();
                            } else {
                                // \Log::channel('gapLog')->info('Error : '.$response['error']);
                                $err_data = [
                                    'module'=>'lot_image',
                                    'reference_id'=>$lot_id,
                                    'action'=>'save_lot_image',
                                    'error_name'=>'Error for GAP addImageUrlToLot',
                                    'error'=>$response['error'],
                                    'description'=>$item_image->full_path,
                                ];
                                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());

                                // if ($this->attempts() > $this->tries) {
                                //     \Log::channel('gapLog')->info('extended 10 seconds');
                                //     $this->release(10);
                                // }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - AddImageUrlToLotJob');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('gapLog')->error('======= Failed - AddImageUrlToLotJob '. $this->item_id .'=======');
    }
}
