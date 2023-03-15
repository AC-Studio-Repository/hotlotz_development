<?php

namespace App\Modules\Xero\Listeners;

use App\Jobs\Xero\WebhookJob;
use Illuminate\Support\Facades\Log;
use App\Modules\Xero\Events\XeroWebhookEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class XeroWebhookListener implements ShouldQueue
{
    public $queue = 'webhook';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function handle(XeroWebhookEvent $event)
    {
        \Log::channel('xeroLog')->info('Web Hook Event Started');

        dispatch((new WebhookJob($event->getEvents))->onQueue('webhook'));

        \Log::channel('xeroLog')->info('Web Hook Event Ended');
    }
}
