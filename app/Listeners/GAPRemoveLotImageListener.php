<?php

namespace App\Listeners;

use App\Helpers\NHelpers;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Exceptions\QueueFailReport;
use App\Events\GAPRemoveLotImageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GAPRemoveLotImageListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;
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
     * @param  GAPRemoveLotImageEvent  $event
     * @return void
     */
    public function handle(GAPRemoveLotImageEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GAPRemoveLotImageEvent');

        try {
            $lot_image_id = $event->lot_image_id;
            \Log::channel('gapLog')->info('LotImageId : '.$lot_image_id);

            $result = Item::removeLotImage($lot_image_id);

            if (isset($result['error'])) {
                \Log::channel('gapLog')->info('GAP Remove Lot Image ERROR : '.$lot_image_id);

                $err_data = [
                    'module'=>'lot_image',
                    'reference_id'=>$lot_image_id,
                    'action'=>'delete',
                    'error_name'=>'Error for GAP removeLotImage',
                    'error'=>$result['error'],
                    'description'=>'Exception when calling LotsApi->removeLotImage',
                ];
                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
            } 
            // else {
            //     DB::table('gap_errors')->where('reference_id', $lot_image_id)->where('module', 'lot_image')->where('action', 'delete')->delete();

            //     \Log::channel('gapLog')->info('Success : GAP Lot Image Removed Successfully!');
            // }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::info('End - GAPRemoveLotImageEvent');
    }
}
