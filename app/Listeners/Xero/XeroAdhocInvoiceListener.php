<?php

namespace App\Listeners\Xero;

use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Xero\XeroAdhocInvoiceEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Customer\Models\CustomerInvoiceItem;

class XeroAdhocInvoiceListener
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
     * @param  XeroAdhocInvoiceEvent  $event
     * @return void
     */
    public function handle(XeroAdhocInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('Adhoc Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Adhoc Invoice Event '. print_r($event->payload, true) .'=======');

        $payload = $event->payload;

        try {
            $createInvoices = $this->xeroInvoiceRepository->createAdhocInvoice($payload, $this->xeroCredentials->getTenantId(), $this->apiInstance);
            \Log::channel('xeroLog')->info($createInvoices);

            \Log::channel('xeroLog')->info('Adhoc Event Ended');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
