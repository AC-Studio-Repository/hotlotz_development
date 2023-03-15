<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\GAPUpdateLotEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemImage;
use App\Events\GapAddImageUrlToLotEvent;
use App\Helpers\NHelpers;
use DB;

class GAPUpdateLotListener implements ShouldQueue
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
     * @param  GAPUpdateLotEvent  $event
     * @return void
     */
    public function handle(GAPUpdateLotEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GAPUpdateLotEvent');

        try {
            $item = $event->item;
            // \Log::channel('gapLog')->info('Item : '.print_r($item, true));

            $auction_id = $event->auction_id;
            \Log::channel('gapLog')->info('SR AuctionId : '.print_r($auction_id, true));

            $sr_auction_id = $event->sr_auction_id;
            \Log::channel('gapLog')->info('SR AuctionId : '.print_r($sr_auction_id, true));

            $lot_id = $event->lot_id;
            \Log::channel('gapLog')->info('LotId : '.print_r($lot_id, true));

            $lot_number = AuctionItem::whereNull('deleted_at')->where('lot_id', $lot_id)->pluck('lot_number')->first();
            \Log::channel('gapLog')->info('LotNumber : '.print_r($lot_number, true));

            $result = Item::updateLot($item, $auction_id, $sr_auction_id, $lot_id, $lot_number);

            if (isset($result['error'])) {
                \Log::channel('gapLog')->info('Error : '.$result['error']);
                $err_data = [
                    'module'=>'lot',
                    'reference_id'=>$item->id,
                    'action'=>'update',
                    'error_name'=>'Error for GAP updateLot',
                    'error'=>$result['error'],
                    'description'=>'Exception when calling LotsApi->updateLot',
                ];
                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
            } else {
                $lot = Item::getLot($lot_id);

                $end_time_utc = NHelpers::changeJsonDateTimeToPhpDateTime($lot['end_time_utc']);
                \Log::channel('gapLog')->info('end_time_utc : '.print_r($end_time_utc, true));

                $data = [
                    'sr_lot_data' => $lot,
                    'end_time_utc' => $end_time_utc,
                ];
                AuctionItem::where('item_id', $item->id)->where('lot_id', $lot_id)->update($data + NHelpers::updated_at_by());

                $auction_id = $event->auction_id;
                \Log::channel('gapLog')->info('AuctionId : '.print_r($auction_id, true));

                // ### Add Images to Lot
                event(new GapAddImageUrlToLotEvent($item->id, $lot_id, $auction_id, $sr_auction_id));

                DB::table('gap_errors')->where('reference_id', $item->id)->where('module', 'lot')->where('action', 'update')->delete();
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - GAPUpdateLotEvent');
    }
}
