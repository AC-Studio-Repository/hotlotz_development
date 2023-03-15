<?php

namespace App\Listeners\Xero;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Xero\XeroWithdrawInvoiceEvent;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;

class XeroWithdrawInvoiceListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        XeroInvoiceRepository $xeroInvoiceRepository
    ) {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    /**
     * Handle the event.
     *
     * @param  XeroWithdrawInvoiceEvent  $event
     * @return void
     */
    public function handle(XeroWithdrawInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('Xero Withdraw Event Started');
        \Log::channel('xeroLog')->info('======= Payload - Xero Withdraw Event '. print_r($event->payload, true) .'=======');

        try {
            $payload = $event->payload;
            $itemFrom = $event->itemFrom;

            $withdraw = $this->xeroInvoiceRepository->withdrawInvoice($payload, $itemFrom);

            \Log::channel('xeroLog')->info($withdraw);
            \Log::channel('xeroLog')->info('Xero Withdraw Event Ended');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
