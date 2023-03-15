<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],


        ## Auction Events
        'App\Events\AuctionCreatedEvent' => [
            'App\Listeners\AuctionCreatedListener',
        ],
        'App\Events\AuctionUpdatedEvent' => [
            'App\Listeners\AuctionUpdatedListener',
        ],
        'App\Events\Auction\LotReorderEvent' => [
            'App\Listeners\Auction\LotReorderListener',
        ],


        ## Customer Events
        'App\Events\CustomerCreatedEvent' => [
            'App\Listeners\CustomerCreatedListener',
        ],
        'App\Events\CustomerUpdatedEvent' => [
            'App\Listeners\CustomerUpdatedListener',
        ],


        ## Category Events
        'App\Events\CategoryCreatedEvent' => [
            'App\Listeners\CategoryCreatedListener',
        ],
        'App\Events\CategoryUpdatedEvent' => [
            'App\Listeners\CategoryUpdatedListener',
        ],


        ## Item Events
        'App\Events\ItemCreatedEvent' => [
            'App\Listeners\ItemCreatedListener',
        ],
        'App\Events\ItemUpdatedEvent' => [
            'App\Listeners\ItemUpdatedListener',
        ],
        'App\Events\ItemCataloguingNeededEvent' => [
            'App\Listeners\ItemCataloguingNeededListener',
        ],
        'App\Events\Item\SaveItemImageToS3Event' => [
            'App\Listeners\Item\SaveItemImageToS3Listener',
        ],
        'App\Events\Item\SaveItemVideoToS3Event' => [
            'App\Listeners\Item\SaveItemVideoToS3Listener',
        ],
        'App\Events\Item\SaveItemInternalPhotoToS3Event' => [
            'App\Listeners\Item\SaveItemInternalPhotoToS3Listener',
        ],
        'App\Events\Item\CreateThumbnailEvent' => [
            'App\Listeners\Item\CreateThumbnailListener',
        ],
        ## Item Email Events
        'App\Events\Item\SubmissionReceivedEvent' => [
            'App\Listeners\Item\SubmissionReceivedListener',
        ],
        'App\Events\Item\ConfirmationEvent' => [
            'App\Listeners\Item\ConfirmationListener',
        ],
        'App\Events\Item\DroppedOffItemReceivedEvent' => [
            'App\Listeners\Item\DroppedOffItemReceivedListener',
        ],
        'App\Events\Item\SellerAgreementEvent' => [
            'App\Listeners\Item\SellerAgreementListener',
        ],
        'App\Events\Item\SellerAgreementConfirmationEvent' => [
            'App\Listeners\Item\SellerAgreementConfirmationListener',
        ],
        'App\Events\Item\ConsignmentUpdateEvent' => [
            'App\Listeners\Item\ConsignmentUpdateListener',
        ],
        'App\Events\Item\StorageEvent' => [
            'App\Listeners\Item\StorageListener',
        ],
        'App\Events\Item\StorageFeeEvent' => [
            'App\Listeners\Item\StorageFeeListener',
        ],
        'App\Events\Item\DeclinedItemEvent' => [
            'App\Listeners\Item\DeclinedItemListener',
        ],
        'App\Events\Item\WithdrawItemEvent' => [
            'App\Listeners\Item\WithdrawItemListener',
        ],
        'App\Events\Item\CancelSaleItemEvent' => [
            'App\Listeners\Item\CancelSaleItemListener',
        ],
        'App\Events\Item\RecentlyConsignedEvent' => [
            'App\Listeners\Item\RecentlyConsignedListener',
        ],

        ## Item Lifecycle Events
        'App\Events\ItemLifecycleStartEvent' => [
            'App\Listeners\ItemLifecycleStartListener',
        ],
        'App\Events\ItemLifcycleStageFinishEvent' => [
            'App\Listeners\ItemLifcycleStageFinishListener',
        ],
        'App\Events\ItemLifcycleNextStageChangeEvent' => [
            'App\Listeners\ItemLifcycleNextStageChangeListener',
        ],
        'App\Events\ItemHistoryEvent' => [
            'App\Listeners\ItemHistoryListener',
        ],


        ## Email Events
        'App\Events\EmailTemplateActionEvent' => [
            'App\Listeners\EmailTemplateActionListener',
        ],
        'App\Events\EmailTemplateEvent' => [
            'App\Listeners\EmailTemplateListener',
        ],


        ## SysConfig Events
        'App\Events\SysConfigActionEvent' => [
            'App\Listeners\SysConfigActionListener',
        ],


        ## GAP Auction APIs Events
        'App\Events\GAPAuctionCreateEvent' => [
            'App\Listeners\GAPAuctionCreateListener',
        ],
        'App\Events\GAPAuctionUpdateEvent' => [
            'App\Listeners\GAPAuctionUpdateListener',
        ],
        'App\Events\GAPAuctionPublishEvent' => [
            'App\Listeners\GAPAuctionPublishListener',
        ],
        'App\Events\UpdateClosedAuctionStatusEvent' => [
            'App\Listeners\UpdateClosedAuctionStatusListener',
        ],
        ## Old command for Check and Update Auction Status
        // 'App\Events\CheckAuctionStatusEvent' => [
        //     'App\Listeners\CheckAuctionStatusListener',
        // ],
        ## New Commands for Check and Update Auction Status
        'App\Events\Auction\AuctionApproveEvent' => [
            'App\Listeners\Auction\AuctionApproveListener',
        ],
        'App\Events\Auction\AuctionPublishEvent' => [
            'App\Listeners\Auction\AuctionPublishListener',
        ],

        ## GAP Lot APIs Events
        'App\Events\GAPCreateLotEvent' => [
            'App\Listeners\GAPCreateLotListener',
        ],
        'App\Events\GAPUpdateLotEvent' => [
            'App\Listeners\GAPUpdateLotListener',
        ],
        'App\Events\GapAddImageUrlToLotEvent' => [
            'App\Listeners\GapAddImageUrlToLotListener',
        ],
        'App\Events\GAPDeleteLotEvent' => [
            'App\Listeners\GAPDeleteLotListener',
        ],
        'App\Events\GAPRemoveLotImageEvent' => [
            'App\Listeners\GAPRemoveLotImageListener',
        ],

        ## Xero Events
        'App\Events\Xero\XeroContactEvent' => [
            'App\Listeners\Xero\XeroContactListener',
        ],
        'App\Events\Xero\XeroProductEvent' => [
            'App\Listeners\Xero\XeroProductListener',
        ],
        'App\Events\Xero\XeroAuctionInvoiceEvent' => [
            'App\Listeners\Xero\XeroAuctionInvoiceListener',
        ],
         'App\Events\Xero\XeroMarketPlaceInvoiceEvent' => [
            'App\Listeners\Xero\XeroMarketPlaceInvoiceListener',
        ],
         'App\Events\Xero\XeroAdhocInvoiceEvent' => [
            'App\Listeners\Xero\XeroAdhocInvoiceListener',
        ],
        'App\Events\Xero\XeroInvoiceCancelEvent' => [
            'App\Listeners\Xero\XeroInvoiceCancelListener',
        ],
        'App\Events\Xero\XeroPaidedInvoiceEvent' => [
            'App\Listeners\Xero\XeroPaidInvoiceListener',
        ],
        'App\Events\Xero\CreateMarketPlaceInvoiceEvent' => [
            'App\Listeners\Xero\CreateMarketPlaceInvoiceListener',
        ],
        'App\Events\Xero\CreateAuctionInvoiceEvent' => [
            'App\Listeners\Xero\CreateAuctionInvoiceListener',
        ],
        'App\Events\Xero\XeroWithdrawInvoiceEvent' => [
            'App\Listeners\Xero\XeroWithdrawInvoiceListener',
        ],
        'App\Events\Xero\QueueCommandEvent' => [
            'App\Listeners\Xero\QueueCommandListener',
        ],
        'App\Events\Xero\XeroPrivateSaleInvoiceEvent' => [
            'App\Listeners\Xero\XeroPrivateSaleInvoiceListener',
        ],
        'App\Events\Xero\ThirdPartyPaymentAlertEvent' => [
            'App\Listeners\Xero\ThirdPartyPaymentAlertListener',
        ],

        ## Client Events
        'App\Events\Client\WelcomeEvent' => [
            'App\Listeners\Client\WelcomeListener',
        ],
        'App\Events\Client\AccountActivateEvent' => [
            'App\Listeners\Client\AccountActivateListener',
        ],
        'App\Events\Client\SettlementEvent' => [
            'App\Listeners\Client\SettlementListener',
        ],
        'App\Events\Client\ForgetPasswordEvent' => [
            'App\Listeners\Client\ForgetPasswordListener',
        ],
        'App\Events\Client\PaymentReceiptEvent' => [
            'App\Listeners\Client\PaymentReceiptListener',
        ],
        'App\Events\Client\SaleroomCustomerRegisterEvent' => [
            'App\Listeners\Client\SaleroomCustomerRegisterListener',
        ],
        'App\Events\Client\AuctionInvoiceEvent' => [
            'App\Listeners\Client\AuctionInvoiceListener',
        ],
        'App\Events\Client\PrivateInvoiceEvent' => [
            'App\Listeners\Client\PrivateInvoiceListener',
        ],
        'App\Events\Client\BankAccountUpdateAlertEvent' => [
            'App\Listeners\Client\BankAccountUpdateAlertListener',
        ],
        'App\Events\Client\ProfileUpdateAlertEvent' => [
            'App\Listeners\Client\ProfileUpdateAlertListener',
        ],
        'App\Events\Client\SendKycIndividualSellerEmailEvent' => [
            'App\Listeners\Client\SendKycIndividualSellerEmailListener',
        ],
        'App\Events\Client\SendKycCompanySellerEmailEvent' => [
            'App\Listeners\Client\SendKycCompanySellerEmailListener',
        ],
        'App\Events\Client\SendKycBuyerEmailEvent' => [
            'App\Listeners\Client\SendKycBuyerEmailListener',
        ],

        ## Mailchimp Events
        'App\Events\Mailchimp\AddOrUpdateEvent' => [
            'App\Listeners\Mailchimp\AddOrUpdateListener',
        ],
        'App\Events\Mailchimp\SubscriberAddOrUpdateEvent' => [
            'App\Listeners\Mailchimp\SubscriberAddOrUpdateListener',
        ],

        ## Admin Events
        'App\Events\Admin\MarketplaceSoldItemListEmailEvent' => [
            'App\Listeners\Admin\MarketplaceSoldItemListEmailListener',
        ],
        'App\Events\Admin\SalesContractAlertEvent' => [
            'App\Listeners\Admin\SalesContractAlertListener',
        ],
        'App\Events\Admin\BankTransferPaynowCheckoutAlertEvent' => [
            'App\Listeners\Admin\BankTransferPaynowCheckoutAlertListener',
        ],
        'App\Events\Admin\KycUpdateAlertEvent' => [
            'App\Listeners\Admin\KycUpdateAlertListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        ##
    }
}