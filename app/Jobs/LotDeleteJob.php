<?php

namespace App\Jobs;

use App\Helpers\NHelpers;
use Illuminate\Bus\Queueable;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Exceptions\QueueFailReport;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LotDeleteJob implements ShouldQueue
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
    public $lot_id;
    public function __construct($lot_id)
    {
        $this->lot_id = $lot_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('gapLog')->info('Start - LotDeleteJob');

        try {
            $lot_id = $this->lot_id;
            \Log::channel('gapLog')->info('LotId : '.$lot_id);

            $lot = Item::getLot($lot_id);

            if(isset($lot) && !isset($lot['error'])){

                $result = Item::deleteLot($lot_id);

                if (isset($result['error'])) {
                    // \Log::channel('gapLog')->info('Error : '.$result['error']);
                    $err_data = [
                        'module'=>'lot',
                        'reference_id'=>$lot_id,
                        'action'=>'delete',
                        'error_name'=>'Error for GAP deleteLot',
                        'error'=>$result['error'],
                        'description'=>'Exception when calling LotsApi->deleteLot',
                    ];
                    DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());

                    // if ($this->attempts() > $this->tries) {
                    //     \Log::channel('gapLog')->info('extended 10 seconds');
                    //     $this->release(10);
                    // }

                }else {
                    DB::table('gap_errors')->where('reference_id', $lot_id)->where('module', 'lot')->where('action', 'delete')->delete();

                    \Log::channel('gapLog')->info('Success : GAP Lot Deleted Successfully!');
                }
            }

        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - LotDeleteJob');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('gapLog')->error('======= Failed - LotDelete Job '. $this->lot_id .'=======');
    }
}
