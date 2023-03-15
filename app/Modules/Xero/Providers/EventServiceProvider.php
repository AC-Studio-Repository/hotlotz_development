<?php

namespace App\Modules\Xero\Providers;

use App\Modules\Xero\Events\XeroWebhookEvent;
use App\Modules\Xero\Listeners\XeroWebhookListener;
use App\Modules\Xero\Events\Invoice\AuctionInvoiceEvent;
use App\Modules\Xero\Events\Invoice\MarketplaceInvoiceEvent;
use App\Modules\Xero\Events\Invoice\PrivateSaleInvoiceEvent;
use App\Modules\Xero\Events\Settlement\AuctionSettlementEvent;

use App\Modules\Xero\Listeners\Invoice\AuctionInvoiceListener;
use App\Modules\Xero\Events\Settlement\MarketplaceSettlementEvent;
use App\Modules\Xero\Events\Settlement\PrivateSaleSettlementEvent;
use App\Modules\Xero\Listeners\Invoice\MarketplaceInvoiceListener;
use App\Modules\Xero\Listeners\Invoice\PrivateSaleInvoiceListener;
use App\Modules\Xero\Listeners\Settlement\AuctionSettlementListener;
use App\Modules\Xero\Events\BillWasPublished as BillWasPublishedEvent;
use App\Modules\Xero\Events\InvoiceWasUpdated as InvoiceWasUpdatedEvent;
use App\Modules\Xero\Listeners\Settlement\MarketplaceSettlementListener;
use App\Modules\Xero\Listeners\Settlement\PrivateSaleSettlementListener;
use App\Modules\Xero\Listeners\BillWasPublished as BillWasPublishedListner;
use App\Modules\Xero\Events\InvoiceWasPublished as InvoiceWasPublishedEvent;
use App\Modules\Xero\Listeners\InvoiceWasUpdated as InvoiceWasUpdatedListner;
use App\Modules\Xero\Listeners\InvoiceWasPublished as InvoiceWasPublishedListner;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
            InvoiceWasUpdatedEvent::class => [
                InvoiceWasUpdatedListner::class
            ],
            InvoiceWasPublishedEvent::class => [
                InvoiceWasPublishedListner::class
            ],
            BillWasPublishedEvent::class => [
                BillWasPublishedListner::class
            ],
            XeroWebhookEvent::class => [
                XeroWebhookListener::class
            ],

            AuctionInvoiceEvent::class => [
                AuctionInvoiceListener::class
            ],
            MarketplaceInvoiceEvent::class => [
                MarketplaceInvoiceListener::class
            ],
            PrivateSaleInvoiceEvent::class => [
                PrivateSaleInvoiceListener::class
            ],

            AuctionSettlementEvent::class => [
                AuctionSettlementListener::class
            ],
            MarketplaceSettlementEvent::class => [
                MarketplaceSettlementListener::class
            ],
            PrivateSaleSettlementEvent::class => [
                PrivateSaleSettlementListener::class
            ],
    ];
}
