<?php

namespace App\Listeners\Xero;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Xero\XeroAuctionInvoiceEvent;
use App\Modules\Xero\Repositories\XeroControlRepository;

class XeroAuctionInvoiceListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(XeroControlRepository $xeroControlRepository)
    {
         $this->xeroControlRepository = $xeroControlRepository;
    }

    /**
     * Handle the event.
     *
     * @param  XeroAuctionInvoiceEvent  $event
     * @return void
     */
    public function handle(XeroAuctionInvoiceEvent $event
    )
    {
        $payload = $event->payload;

        \Log::channel('xeroLog')->info('Xero Invoice Auction Event Started');

        \Log::channel('xeroLog')->info('======= Start - Auction Invoice Job '. $payload['auction_id']  .'=======');
        \Log::channel('xeroLog')->info('======= Payload - Auction Invoice Job '. print_r($payload, true) .'=======');

        try {
            $saveInvoice = $this->xeroControlRepository->saveAuctionInvoice($payload);
            \Log::channel('xeroLog')->info($saveInvoice);
            \Log::channel('xeroLog')->info('======= End - Auction Invoice Job '. $payload['auction_id'] .'=======');
        } catch (\Exception $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            throw $e;
        }

        \Log::channel('xeroLog')->info('Xero Invoice Auction Event Ended');
    }

    /**
     * Handle a job failure.
     *
     * @param  XeroAuctionInvoiceEvent   $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(XeroAuctionInvoiceEvent $event, $exception)
    {
        \Log::channel('xeroLog')->error("Caught Exception ('{$exception->getMessage()}')\n{$exception}\n");
    }
}
