<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Jobs\AddImageUrlToLotJob;
use App\Events\GAPRemoveLotImageEvent;
use App\Helpers\NHelpers;
use DB;

class AddImageUrlToLot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:image_url_to_lot {auction_id} {--item_id=} {--item_ids=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add image url to lot';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - AddImageUrlToLot Command =======');
        \Log::channel('gapLog')->info('======= Start - AddImageUrlToLot Command =======');

        $auction_id = $this->argument('auction_id');
        \Log::channel('gapLog')->info('auction_id : '.$auction_id);

        $auction = Auction::find($auction_id);

        if($auction != null && $auction->sr_auction_id != null && $auction->is_closed != "Y"){
            \Log::channel('gapLog')->info('sr_auction_id : '.$auction->sr_auction_id);

            $gap_auction = Auction::getAuctionById($auction->sr_auction_id);
            if( isset($gap_auction['error']) ){
                \Log::channel('gapLog')->error('Auction_'.$auction_id.' is not exist in Toolbox');
            }
            if( !isset($gap_auction['error']) ){
                if($this->option('item_id') != null){
                    $item_id = $this->option('item_id');
                    $this->addImageUrlToLotByItemId($auction_id, $auction->sr_auction_id, $item_id);
                }

                if($this->option('item_ids') != null){
                    $item_ids_option = $this->option('item_ids');
                    $item_ids = explode(",", $item_ids_option);
                    foreach ($item_ids as $key => $item_id) {
                        $this->addImageUrlToLotByItemId($auction_id, $auction->sr_auction_id, $item_id);
                    }
                }
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - AddImageUrlToLot Command =======');
        \Log::channel('gapLog')->info('======= End - AddImageUrlToLot Command =======');
    }

    protected function addImageUrlToLotByItemId($auction_id, $sr_auction_id, $item_id)
    {
        \Log::channel('gapLog')->info('item_id : '.$item_id);
        $item = Item::find($item_id);
        if($item != null){            
            $lotimages = DB::table('lot_images')->whereNull('deleted_at')->where('item_id', $item_id)->where('auction_id', $auction_id)->get();

            DB::table('lot_images')->whereNull('deleted_at')->where('item_id', $item_id)->where('auction_id', $auction_id)->delete();

            if (count($lotimages)>0) {
                foreach ($lotimages as $key => $lotimage) {
                    if ($lotimage->lot_image_id != null) {
                        \Log::channel('gapLog')->info('call GAPRemoveLotImageEvent');
                        event(new GAPRemoveLotImageEvent($lotimage->lot_image_id));
                    }
                }                
            }

            $auction_item = AuctionItem::where('auction_id',$auction_id)
                            ->where('item_id',$item_id)
                            ->whereNotNull('lot_id')
                            ->first();
            if($auction_item != null){
                $lot_id = $auction_item->lot_id;
                \Log::channel('gapLog')->info('dispatch AddImageUrlToLotJob');
                AddImageUrlToLotJob::dispatch($item_id, $lot_id, $auction_id, $sr_auction_id);
            }
        }
    }
}
