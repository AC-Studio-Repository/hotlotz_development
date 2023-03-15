<?php

namespace App\Jobs\Xero;

use Exception;
use Illuminate\Bus\Queueable;
use App\Jobs\Xero\InvoiceUpdateJob;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Xero\Events\InvoiceWasUpdated;

class WebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function tags()
    {
        return ['render', 'xero webhook'];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('xeroLog')->info('======= Start - Xero Webhook Job =======');

        try {
            $payload = $this->payload;
            foreach ($payload['events'] as $event) {
                if ($event['eventType'] === 'UPDATE' && $event['eventCategory'] === 'INVOICE') {
                    dispatch((new InvoiceUpdateJob($event['resourceId']))->onQueue('webhook'));
                }
            }
            \Log::channel('xeroLog')->info('======= End - Xero Webhook Job =======');
        } catch (\Exception $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            throw $e;
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        app(\App\Exceptions\QueueFailReport::class)->report($exception);

        \Log::channel('xeroLog')->error('======= Failed - Xero Webhook Job =======');
    }
}
