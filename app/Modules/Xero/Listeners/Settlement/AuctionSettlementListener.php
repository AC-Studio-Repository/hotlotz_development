<?php

namespace App\Modules\Xero\Listeners\Settlement;

use App\Modules\Item\Models\Item;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Xero\Events\Settlement\AuctionSettlementEvent;

class AuctionSettlementListener
{

    protected $xeroInvoiceRepository;

    public function __construct(XeroInvoiceRepository $xeroInvoiceRepository)
    {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    public function handle(AuctionSettlementEvent $event)
    {
        \Log::channel('xeroLog')->info('AuctionSettlementEvent Started');

        $auction_id = $event->auction_id;

        $item_number = $event->item_number;

        $date = $event->date;

        $item = Item::where('item_number', $item_number)->first();

        $ref = "Auction Invoice " . $auction_id;

        if($item && $item->sold_price){
             $this->xeroInvoiceRepository->sellerXeroInvoice($item->customer_id, [$item], [$item->sold_price], $ref, $auction_id, 'auction', 'auction', $date);
        }

        \Log::channel('xeroLog')->info('AuctionSettlementEvent Ended');
    }
}
