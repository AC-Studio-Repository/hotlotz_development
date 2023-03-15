<?php

namespace App\Jobs\Xero;

use Exception;
use Illuminate\Bus\Queueable;
use App\Exceptions\QueueFailReport;
use App\Events\Xero\QueueCommandEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class QueueCommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function tags()
    {
        return ['render', 'queue command'];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('xeroLog')->info('======= Start - Xero Queue Command Job =======');
        try {
            Artisan::queue('xero:queue')->onConnection('redis')->onQueue('xero');
            event(new QueueCommandEvent());
            \Log::channel('xeroLog')->info('======= End - Xero Queue Command Job =======');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        \Log::channel('xeroLog')->error('======= Failed - Xero Queue Command Job =======');
    }
}
