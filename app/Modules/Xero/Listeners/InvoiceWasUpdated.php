<?php

namespace App\Modules\Xero\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Xero\Webhook\Updated as XeroWebhookUpdate;
use App\Modules\Xero\Events\InvoiceWasUpdated as InvoiceWasUpdatedEvent;

class InvoiceWasUpdated implements ShouldQueue
{
    public $apiInstance;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        XeroWebhookUpdate $xeroWebhookUpdate
    ) {
        $this->xeroWebhookUpdate = $xeroWebhookUpdate;
    }

    public function init($arg)
    {
        $this->apiInstance = $arg;
    }

    public function handle(InvoiceWasUpdatedEvent $event)
    {
        \Log::channel('xeroLog')->info('Web Hook Invoice Update Event Started');
        $this->xeroWebhookUpdate->invoiceUpdated($event->invoice);
    }
}
