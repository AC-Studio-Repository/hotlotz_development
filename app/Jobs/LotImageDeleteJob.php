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

class LotImageDeleteJob implements ShouldQueue
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
    public $lot_image_id;
    public function __construct($lot_image_id)
    {
        $this->lot_image_id = $lot_image_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('gapLog')->info('Start - LotImageDeleteJob');

        try {
            $lot_image_id = $this->lot_image_id;
            \Log::channel('gapLog')->info('LotId : '.$lot_image_id);

            $result = Item::removeLotImage($lot_image_id);

            if(isset($result['error'])){
                
                // \Log::channel('gapLog')->info('Error : '.$result['error']);

                $err_data = [
                    'module'=>'lot_image',
                    'reference_id'=>$lot_image_id,
                    'action'=>'delete',
                    'error_name'=>'Error for GAP removeLotImage',
                    'error'=>$result['error'],
                    'description'=>'Exception when calling LotsApi->removeLotImage',
                ];
                DB::table('gap_errors')->insert($err_data + NHelpers::created_updated_at_by());

                throw new QueueFailReport($result['error']);
                
            }else{
                DB::table('gap_errors')->where('reference_id',$lot_image_id)->where('module','lot_image')->where('action','delete')->delete();
                
                \Log::channel('gapLog')->info('Success : GAP Lot Image Removed Successfully!');
            }

        } catch (\Exception $e) {
            \Log::channel('gapLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('gapLog')->info('End - LotImageDeleteJob');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('gapLog')->error('======= Failed - LotImageDeleteJob '. $this->lot_image_id .'=======');
    }
}
