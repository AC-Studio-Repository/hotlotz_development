<?php

namespace App\Modules\Xero\Listeners\Settlement;

use App\Modules\Item\Models\Item;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Xero\Events\Settlement\MarketplaceSettlementEvent;

class MarketplaceSettlementListener
{
    protected $xeroInvoiceRepository;

    public function __construct(XeroInvoiceRepository $xeroInvoiceRepository)
    {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    public function handle(MarketplaceSettlementEvent $event)
    {
        \Log::channel('xeroLog')->info('MarketplaceSettlementEvent Started');

        $item_number = $event->item_number;

        $date = $event->date;

        $item = Item::where('item_number', $item_number)->first();

        if ($item && $item->sold_price) {
            $this->xeroInvoiceRepository->sellerXeroInvoice($item->customer_id, [$item], [$item->sold_price], 'Hotlotz Marketplace', null, 'marketplace', 'marketplace', $date);
        }

        \Log::channel('xeroLog')->info('MarketplaceSettlementEvent Ended');
    }
}
