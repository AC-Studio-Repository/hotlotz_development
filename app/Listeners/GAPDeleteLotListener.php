<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\GAPDeleteLotEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Helpers\NHelpers;
use DB;

class GAPDeleteLotListener implements ShouldQueue
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
     * @param  GAPDeleteLotEvent  $event
     * @return void
     */
    public function handle(GAPDeleteLotEvent $event)
    {
        \Log::channel('gapLog')->info('Start - GAPDeleteLotEvent');

        try {
            $lot_id = $event->lot_id;
            \Log::channel('gapLog')->info('LotId : '.print_r($lot_id, true));

            $result = Item::deleteLot($lot_id);
            if (isset($result['error'])) {
                \Log::channel('gapLog')->info('Error : '.$result['error']);
                $err_data = [
                    'module'=>'lot',
                    'reference_id'=>$lot_id,
                    'action'=>'delete',
                    'error_name'=>'Error for GAP deleteLot',
                    'error'=>$result['error'],
                    'description'=>'Exception when calling LotsApi->deleteLot',
                ];
                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());
            } else {
                DB::table('gap_errors')->where('reference_id', $lot_id)->where('module', 'lot')->where('action', 'delete')->delete();

                \Log::channel('gapLog')->info('Success : GAP Lot Deleted Successfully!');
            }
        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - GAPDeleteLotEvent');
    }
}
