<?php

namespace App\Listeners\Xero;

use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Xero\XeroPrivateSaleInvoiceEvent;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;

class XeroPrivateSaleInvoiceListener
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
     * @param  XeroPrivateSaleInvoiceEvent  $event
     * @return void
     */
    public function handle(XeroPrivateSaleInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('Private Sale Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Private Sale Event '. print_r($event->payload, true) .'=======');

        $payload = $event->payload;
        $multiType = $event->multiType;

        try {
            if($multiType){
                $this->xeroInvoiceRepository->createPrivateSaleInvoiceMultiItems($payload);
            }else{
                $this->xeroInvoiceRepository->createPrivateSaleInvoice($payload);
            }
            \Log::channel('xeroLog')->info('Private Sale Event Ended');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
