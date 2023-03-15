<?php

namespace App\Listeners\Xero;

use App\Events\Xero\XeroContactEvent;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Xero\Repositories\XeroContactRepository;

class XeroContactListener
{
    public $apiInstance;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        XeroContactRepository $xeroContactRepository,
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance
    ) {
        $this->xeroContactRepository = $xeroContactRepository;
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
     * @param  XeroContactEvent  $event
     * @return void
     */
    public function handle(XeroContactEvent $event)
    {
        \Log::channel('xeroLog')->info('Contact Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Contact Event '. print_r($event->payload, true) .'=======');

        try {
            $xeroTenantId = $this->xeroCredentials->getTenantId();
            $payload = $event->payload;

            $contact = $this->xeroContactRepository->createOrUpdateContact($payload, $xeroTenantId, $this->apiInstance);

            \Log::channel('xeroLog')->info($contact);
            \Log::channel('xeroLog')->info('Contact Event Ended');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
