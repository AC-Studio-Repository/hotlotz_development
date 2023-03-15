<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\GAPCreateLotEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemImage;
use App\Events\GapAddImageUrlToLotEvent;
use App\Helpers\NHelpers;
use DB;

class GAPCreateLotListener implements ShouldQueue
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
     * @param  GAPCreateLotEvent  $event
     * @return void
     */
    public function handle(GAPCreateLotEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GAPCreateLotEvent');

        try {
            $item_id = $event->item_id;
            \Log::channel('gapLog')->info('Item Id : '.print_r($item_id, true));

            $sr_auction_id = $event->sr_auction_id;
            \Log::channel('gapLog')->info('SR AuctionId : '.print_r($sr_auction_id, true));

            $auction_id = $event->auction_id;
            \Log::channel('gapLog')->info('AuctionId : '.print_r($auction_id, true));

            $item = Item::find($item_id);
            \Log::channel('gapLog')->info('Item status : '.print_r($item->status, true));

            ## New Logic for Lot Number
            $auctionitem = AuctionItem::whereNull('deleted_at')->where('item_id', $item_id)->where('auction_id', $auction_id)->first();

            $lot_number = rand();
            if ($auctionitem) {
                $lot_number = $auctionitem->id;
            }

            \Log::channel('gapLog')->info('Lot Number : '.$lot_number);


            $result = Item::createLot($item, $auction_id, $sr_auction_id, $lot_number);
            // \Log::channel('gapLog')->info('Lot result : '.print_r($result, true));

            if (isset($result['lot_id'])) {
                \Log::channel('gapLog')->info('LotId : '.print_r($result['lot_id'], true));

                $lot = Item::getLot($result['lot_id']);

                $end_time_utc = NHelpers::changeJsonDateTimeToPhpDateTime($lot['end_time_utc']);
                \Log::channel('gapLog')->info('end_time_utc : '.print_r($end_time_utc, true));

                $data = [
                    'lot_id'=>$result['lot_id'],
                    'lot_number'=>$lot_number,
                    'sr_lot_data'=>$lot,
                    'end_time_utc' => $end_time_utc,
                ];
                AuctionItem::where('item_id', $item_id)->where('auction_id', $auction_id)->update($data + NHelpers::updated_at_by());

                // ### Add Images to Lot
                event(new GapAddImageUrlToLotEvent($item_id, $result['lot_id'], $auction_id, $sr_auction_id));

                DB::table('gap_errors')->where('reference_id', $item_id)->where('module', 'lot')->where('action', 'create')->delete();
            } else {
                \Log::channel('gapLog')->info('GAP Error : '.$result['error']);
                $err_data = [
                    'module'=>'lot',
                    'reference_id'=>$item_id,
                    'action'=>'create',
                    'error_name'=>'Error for GAP createLot',
                    'error'=>$result['error'],
                    'description'=>'Exception when calling LotsApi->createLot',
                ];
                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - GAPCreateLotEvent');
    }

    public function failed(\Exception $e)
    {
        \Log::channel('gapLog')->error('Failed Error : '. $e);
    }
}
