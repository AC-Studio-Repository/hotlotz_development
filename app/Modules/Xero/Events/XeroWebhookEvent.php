<?php

namespace App\Modules\Xero\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class XeroWebhookEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var \Webfox\Xero\WebhookEvent[]|\Illuminate\Support\Collection
     */
    public $getEvents;

    public function __construct($getEvents)
    {
        $this->getEvents = $getEvents;
    }
}