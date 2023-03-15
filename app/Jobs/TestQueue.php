<?php

namespace App\Jobs;

use Throwable;
use Exception;
use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use App\Modules\Item\Models\Item;

class TestQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    // public $tries = 5;

    /**
    * The number of seconds to wait before retrying the job.
    *
    * @var int
    */
    // public $retryAfter = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $item_id;
    public function __construct($item_id)
    {
        $this->item_id = $item_id;
    }

    /**     
     * @return Carbon     
     */    
    // public function retryAfter()    
    // {   
    //     return now()->addSeconds(
    //         $this->attempts() + 10
    //     );
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('======= Start - TestQueue Job =======');
        throw new QueueFailReport('Test error message!');
        // try{
            // $redis_job = Redis::get('hotlotz_database_queues:test3:delayed');
            // $redis_job->delete();

            \Log::info('attempts : '.$this->attempts());

            $item_id = $this->item_id;
            \Log::info(print_r($item_id, true));

            $item = Item::find($item_id);
            // \Log::info(print_r($item, true));

            // $jobId = $this->job->getJobId();
            // \Log::info("jobId : ".$jobId);

            // if(isset($item) && $item->permission_to_sell == 'Y'){
                \Log::info('TestQueue Job Successfully!');
            // }else{
                // \Log::info('TestQueue Job Failed!');
                // throw new Exception ('The message params are not valid');
                
                // try{
                // } catch (Exception $exception) {
                //     \Log::error('ERROR exception : '.print_r($exception,true));
                //     throw $exception;
                // }
                // \Log::error('exception : '.print_r($exception,true));
                // $this->job->fail($exception);

                // if ($this->attempts() < $this->tries) {
                //     \Log::info("extended 5 seconds");
                //     $this->release(5);
                // }
            // }

        // } catch (Exception $e) {
        //     \Log::error('ERROR : '.print_r($e,true));
        //     throw $e;
        //     // try{
        //         // \Log::error('ERROR code : '.print_r($e->getCode(),true));
        //     // } catch (Exception $ee) {
        //     //     \Log::error('ERROR 2 : '.print_r($ee,true));
        //     //     throw $ee;
        //     // }
        // }

        \Log::info('======= End - TestQueue Job =======');
    }

    public function failed(Exception $exception)
    {
        \Log::error('======= Failed - TestQueue Job '. print_r($this->item_id,true) .'=======');
    }
}
