<?php

namespace App\Modules\Xero\Listeners\Invoice;

use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Xero\Events\Invoice\MarketplaceInvoiceEvent;

class MarketplaceInvoiceListener
{
    protected $xeroInvoiceRepository;

    public function __construct(XeroInvoiceRepository $xeroInvoiceRepository)
    {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    public function handle(MarketplaceInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('MarketplaceInvoiceEvent Started');

        $payload = $event->payload;

        $only = $event->only;

        $date = $event->date;
        
        $this->xeroInvoiceRepository->createMarketPlaceInvoice($payload, $only, $date);

        \Log::channel('xeroLog')->info('MarketplaceInvoiceEvent Ended');
    }
}
