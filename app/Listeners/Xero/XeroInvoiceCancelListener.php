<?php

namespace App\Listeners\Xero;

use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Xero\XeroInvoiceCancelEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;

class XeroInvoiceCancelListener
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
     * @param  XeroInvoiceCancelEvent  $event
     * @return void
     */
    public function handle(XeroInvoiceCancelEvent $event)
    {
        \Log::channel('xeroLog')->info('Cancel Invoice Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Cancel Invoice Event '. print_r($event->payload, true) .'=======');

        try {
            $xeroTenantId = $this->xeroCredentials->getTenantId();
            $payload = $event->payload;
            $type = $event->type;
            if($type == 'cancel'){
                 $cancelInvoice = $this->xeroInvoiceRepository->cancelInvoice($payload, $xeroTenantId, $this->apiInstance);
            }
            if ($type == 'credit') {
                $cancelInvoice = $this->xeroInvoiceRepository->creditInvoice($payload, $xeroTenantId, $this->apiInstance);
            }

            \Log::channel('xeroLog')->info($cancelInvoice);
            \Log::channel('xeroLog')->info('Cancel Invoice Event Ended');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
