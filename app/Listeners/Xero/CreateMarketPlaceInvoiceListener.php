<?php

namespace App\Listeners\Xero;

use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Xero\CreateMarketPlaceInvoiceEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;

class CreateMarketPlaceInvoiceListener
{
    public $apiInstance;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        XeroInvoiceRepository $xeroInvoiceRepository,
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance
    ) {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
        $this->xeroCredentials = $xeroCredentials;
        $this->apiInstance = $apiInstance;
    }

    public function init($arg)
    {
        $this->apiInstance = $arg;
    }

    /**
     * Handle the event.
     *
     * @param  CreateMarketPlaceInvoiceEvent  $event
     * @return void
     */
    public function handle(CreateMarketPlaceInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('Create Marketplace Invoice Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Marketplace Invoice Event '. print_r($event->payload, true) .'=======');

        $payload = $event->payload;

        try {
            $createInvoices = $this->xeroInvoiceRepository->createMarketPlaceInvoice($payload);
            \Log::channel('xeroLog')->info($createInvoices);
            \Log::channel('xeroLog')->info('Create Marketplace Invoice Event Ended');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
