<?php

namespace App\Listeners\Xero;

use App\Events\Xero\XeroProductEvent;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Xero\Repositories\XeroProductRepository;

class XeroProductListener
{
    public $apiInstance;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        XeroProductRepository $xeroProductRepository,
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance
    ) {
        $this->xeroProductRepository = $xeroProductRepository;
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
     * @param  XeroProductEvent  $event
     * @return void
     */
    public function handle(XeroProductEvent $event)
    {
        \Log::channel('xeroLog')->info('Xero Product Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Xero Product Event '. print_r($event->payload, true) .'=======');

        try {
            $xeroTenantId = $this->xeroCredentials->getTenantId();
            $payload = $event->payload;

            if ($event->isUpdateMode) {
                $updateProduct = $this->xeroProductRepository->updateProduct($payload, $xeroTenantId, $this->apiInstance);

                \Log::channel('xeroLog')->info($updateProduct);
            } else {
                $createProduct = $this->xeroProductRepository->createProduct($payload, $xeroTenantId, $this->apiInstance);

                \Log::channel('xeroLog')->info($createProduct);
                \Log::channel('xeroLog')->info('Xero Product Event Ended');
            }
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
