<?php

namespace App\Listeners\Xero;

use App\Jobs\Xero\MarketPlaceInvoiceJob;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Xero\XeroMarketPlaceInvoiceEvent;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;

class XeroMarketPlaceInvoiceListener
{
    /**
    * Create the event listener.
    *
    * @return void
    */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  XeroMarketPlaceInvoiceEvent  $event
     * @return void
     */
    public function handle(XeroMarketPlaceInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('Marketplace Invoice Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Marketplace Invoice Event '. print_r($event->payload, true) .'=======');

        dispatch((new MarketPlaceInvoiceJob($event->payload))->onQueue('xero'))->delay(now()->addSeconds(5));

        \Log::channel('xeroLog')->info('Marketplace Invoice Event Ended');
    }

    /**
    * Handle a job failure.
    *
    * @param  XeroMarketPlaceInvoiceEvent   $event
    * @param  \Throwable  $exception
    * @return void
    */
    public function failed(XeroMarketPlaceInvoiceEvent $event, $exception)
    {
        \Log::channel('xeroLog')->error("Caught Exception ('{$exception->getMessage()}')\n{$exception}\n");
    }
}
