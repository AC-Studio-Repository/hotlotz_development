<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Helpers\NHelpers;
use DB;

class CheckAndSaveLotImageInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:check_and_save_lot_image_info {auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Save Lot Image Information';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - CheckAndSaveLotImageInfo Command =======');
        \Log::info('======= Start - CheckAndSaveLotImageInfo Command =======');

        $auction_id = $this->argument('auction_id');
        \Log::info('auction_id : '.$auction_id);

        $auction = Auction::find($auction_id);
        if(isset($auction) && $auction->sr_auction_id != null){

            $auction_items = AuctionItem::where('auction_id',$auction_id)->pluck('item_id','lot_id')->all();
            \Log::info('count of auction_items : '.count($auction_items));

            if(count($auction_items) > 0){
                foreach ($auction_items as $lot_id => $item_id) {
                    \Log::info('item_id : '.$item_id);
                    $item_images = ItemImage::where('item_id',$item_id)->pluck('id')->all();

                    \Log::info('lot_id : '.$lot_id);
                    $lot = Item::getLot($lot_id);

                    if (isset($lot) && count($lot['image_ur_ls'])>0) {
                        \Log::info('count of ImageURLs : '.count($lot['image_ur_ls']));
                        // \Log::info('Lot Image_URLs : '.print_r($lot['image_ur_ls'],true));
                        foreach ($lot['image_ur_ls'] as $key => $image_url) {

                            $item_image_id = isset($item_images[$key])? $item_images[$key]:$item_images[0];

                            $existing_image_ids = DB::table('lot_images')->whereNull('deleted_at')->where('item_id', $item_id)->pluck('item_image_id')->all();

                            if ((count($existing_image_ids) <= 0 || !in_array($item_image_id, $existing_image_ids))) {

                                $image_url = (string)$image_url;
                                // \Log::info('Lot image_url : '.print_r($image_url,true));
                                $arr1 = explode("//", $image_url);
                                $arr2 = explode("/", $arr1[1]);
                                $lot_image_id = $arr2[2];
                                // \Log::info('lot_image_id : '.$lot_image_id);

                                $lot_img_data = [
                                    'item_image_id' => $item_image_id,
                                    'item_id' => $item_id,
                                    'auction_id' => $auction_id,
                                    'sr_auction_id' => $auction->sr_auction_id,
                                    'lot_id' => $lot_id,
                                    'lot_image_id' => $lot_image_id,
                                    'file_name' => null,
                                    'file_path' => $image_url,
                                ];
                                DB::table('lot_images')->insert($lot_img_data + NHelpers::created_updated_at_by());
                            }
                        }
                    }
                }
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - CheckAndSaveLotImageInfo Command =======');
        \Log::info('======= End - CheckAndSaveLotImageInfo Command =======');
    }
}
