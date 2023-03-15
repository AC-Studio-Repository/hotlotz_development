<?php

namespace App\Modules\Xero\Listeners\Settlement;

use App\Modules\Item\Models\Item;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Xero\Events\Settlement\PrivateSaleSettlementEvent;

class PrivateSaleSettlementListener
{
    protected $xeroInvoiceRepository;

    public function __construct(XeroInvoiceRepository $xeroInvoiceRepository)
    {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    public function handle(PrivateSaleSettlementEvent $event)
    {
        \Log::channel('xeroLog')->info('PrivateSaleSettlementEvent Started');

        $item_number = $event->item_number;

        $date = $event->date;

        $item = Item::where('item_number', $item_number)->first();

        if ($item && $item->sold_price) {
             $this->xeroInvoiceRepository->sellerXeroInvoice($item->customer_id, [$item], [$item->sold_price], 'Private Sale Invoice ', $auction_id = null, $type = 'private', 'private', $date);
        }

        \Log::channel('xeroLog')->info('PrivateSaleSettlementEvent Ended');
    }
}
