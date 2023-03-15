<?php

namespace App\Modules\Xero\Listeners\Invoice;

use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Xero\Events\Invoice\AuctionInvoiceEvent;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;

class AuctionInvoiceListener
{
    protected $xeroInvoiceRepository;

    public function __construct(XeroInvoiceRepository $xeroInvoiceRepository)
    {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    public function handle(AuctionInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('AuctionInvoiceEvent Started');

        $auction_id = $event->auction_id;

        $item_number = $event->item_number;

        $date = $event->date;

        $item = Item::where('item_number', $item_number)->first();

        $auction = Auction::where('id', $auction_id)->first();

        $ref = "Auction Invoice " . $auction->title;

        if ($item && $item->sold_price && $item->buyer_id) {
            $this->xeroInvoiceRepository->buyerAuctionXeroInvoice($item->buyer_id, [$item], [$item->sold_price], $ref, $auction_id, $date);
        }

        \Log::channel('xeroLog')->info('AuctionInvoiceEvent Ended');
    }
}
