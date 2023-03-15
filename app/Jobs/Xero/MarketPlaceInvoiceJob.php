<?php

namespace App\Jobs\Xero;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Xero\XeroAuctionInvoiceEvent;
use App\Events\Xero\CreateAuctionInvoiceEvent;
use App\Modules\Xero\Repositories\XeroControlRepository;

class MarketPlaceInvoiceJob implements ShouldQueue
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
        return ['render', 'xero invoice: ' . $this->payload['customer_id']];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        XeroControlRepository $xeroControlRepository
    ) {
        \Log::channel('xeroLog')->info('======= Start - Marketplace Invoice Job '. $this->payload['customer_id']  .'=======');
        \Log::channel('xeroLog')->info('======= Payload - Marketplace Invoice Job '. print_r($this->payload, true) .'=======');

        try {
            $payload = $this->payload;
            $saveInvoice = $xeroControlRepository->saveMarketPlaceInvoice($payload);
            \Log::channel('xeroLog')->info($saveInvoice);
            \Log::channel('xeroLog')->info('======= End - Marketplace Invoice Job '. $this->payload['customer_id'] .'=======');
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

        \Log::channel('xeroLog')->error('======= Failed - Marketplace Invoice Job '. print_r($this->payload, true) .'=======');
    }
}
