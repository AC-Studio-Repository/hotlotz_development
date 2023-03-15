<?php

namespace App\Jobs\Xero;

use Cache;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Xero\Events\InvoiceWasUpdated;

class InvoiceUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    /** @var AccountingApi  */
    protected $accountingApi;
    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 0;

    /**
    * The number of seconds after which the job's unique lock will be released.
    *
    * @var int
    */
    public $uniqueFor = 900;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $payload;
    }

    public function tags()
    {
        return ['render', 'xero invoice update: ' . $this->payload];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AccountingApi $accountingApi)
    {
        $lock = Cache::lock($this->payload, $this->uniqueFor);

        if ($lock->get()) {
            $this->accountingApi = $accountingApi;
            \Log::channel('xeroLog')->info('======= Start - Xero Invoice Update Job '. $this->payload .'=======');

            $credentials = app(OauthCredentialManager::class);
            if ($credentials->isExpired()) {
                $xeroConfig = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration();
                $credentials->refresh();
                $xeroConfig->setAccessToken($credentials->getAccessToken());
            }

            try {
                $payload = $this->payload;
                $invoice = $this->accountingApi
                ->getInvoice($credentials->getTenantId(), $payload)
                ->getInvoices()[0];
                event(new InvoiceWasUpdated($invoice));

                Cache::lock($this->payload)->forceRelease();
                \Log::channel('xeroLog')->info('======= End - Xero Invoice Update Job '. $this->payload .'=======');
            } catch (\Exception $e) {
                \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
                Cache::lock($this->payload)->forceRelease();

                throw $e;
            }
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
        Cache::lock($this->payload)->forceRelease();

        app(\App\Exceptions\QueueFailReport::class)->report($exception);

        \Log::channel('xeroLog')->error('======= Failed - Xero Invoice Update Job '. $this->payload .'=======');
    }
}
