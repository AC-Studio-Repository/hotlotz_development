<?php

namespace App\Jobs\Xero;

use Exception;
use Illuminate\Bus\Queueable;
use App\Exceptions\QueueFailReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateInvoiceJob implements ShouldQueue
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
        return ['render', 'generate invoice'];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('xeroLog')->info('======= Start - Generate Invoice Job =======');
        throw new QueueFailReport('Error Queue');
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        \Log::channel('xeroLog')->error('======= Failed - Generate Invoice Job =======');
    }
}
