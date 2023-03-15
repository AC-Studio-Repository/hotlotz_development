<?php

namespace App\Modules\Xero\Listeners\Invoice;

use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Log;
use App\Modules\Customer\Models\Customer;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;
use XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes;
use App\Modules\Xero\Repositories\XeroContactRepository;
use App\Modules\Xero\Repositories\XeroControlRepository;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Xero\Events\Invoice\PrivateSaleInvoiceEvent;

class PrivateSaleInvoiceListener
{
    protected $xeroInvoiceRepository;

    protected $xeroContactRepository;

    protected $xeroControlRepository;

    public function __construct(
        XeroInvoiceRepository $xeroInvoiceRepository,
        XeroContactRepository $xeroContactRepository,
        XeroControlRepository $xeroControlRepository
    )
    {
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
        $this->xeroControlRepository = $xeroControlRepository;
        $this->xeroContactRepository = $xeroContactRepository;
    }

    public function handle(PrivateSaleInvoiceEvent $event)
    {
        \Log::channel('xeroLog')->info('PrivateSaleInvoiceEvent Started');

        $item_number = $event->item_number;

        $item = Item::where('item_number', $item_number)->first();

        $buyer_id = $event->buyer_id;

        $price = $event->price;

        $buyer_premiun = $event->buyer_premiun;

        $date = $event->date;

        $ref = "Private Sale Invoice ";

        $buyerContactID = $this->xeroContactRepository->createOrGetContact($buyer_id);

        $buyerContact = $this->xeroControlRepository->setXeroContact($buyerContactID);

        $customer = Customer::findOrFail($buyer_id);

        $buyerLineItems = $this->xeroControlRepository->getPrivateBuyerLineItems($price, $item, $customer, $buyer_premiun, $itemForm = 'auction');

        $buyerPrivateInvoice = $customer->invoices()->where('auction_id', null)->where('invoice_type', 'private')->where('type', 'invoice')->latest()->first();

        $buyerInvoice = $this->xeroInvoiceRepository->setBuyerInvoice($buyerContact, $buyerLineItems, Invoice::STATUS_AUTHORISED, $buyerPrivateInvoice, $ref, LineAmountTypes::INCLUSIVE, $date);
        \Log::channel('xeroLog')->info('Success Buyer Invoice '.$buyerInvoice->getInvoices()[0]->getInvoiceId());

        $customerInvoice = $this->xeroControlRepository->createCustomerInvoice($buyer_id, $buyerInvoice->getInvoices()[0]->getInvoiceId(), 'private', 'invoice', $buyerInvoice->getInvoices()[0]->__toString());

        $this->xeroControlRepository->createCustomerInvoiceItem($customerInvoice->id, $item->id, $price, 'private');

        $item->invoice_id = $buyerInvoice->getInvoices()[0]->getInvoiceId();
        $item->save();

        \Log::channel('xeroLog')->info('PrivateSaleInvoiceEvent Ended');
    }
}
