<?php

namespace App\Console\Commands\gap;

use Illuminate\Console\Command;

use App\Helpers\NHelpers;

class Auction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Auction API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $this->getAuctionById();
        // $this->getAuctionByReference();
        // $this->getAuctionsByClientId();
        $this->getBiddingHistoryByAuctionId();
        // $this->getSupportedCategories();
    }

    private function createAuction(){
        $auction_object = [
            "LegacyId"=> 0,
            "AuctionCreatedDateTimeUtc"=> "",
            "TimeIsAlreadyUtc"=> false,
            "CreatedByUser"=> "",
            "Title"=> "",
            "ClientId"=> "",
            "AuctionListings"=> [
              [
                "PlatformCode"=> "SR",
                "AuctionTypeAndListing"=> "timed",
                "CategoryName"=> "Collectables",
                "Private"=> true
              ]
            ],
            "TimezoneId"=> "Singapore Standard Time",
            "CardRequired"=> false,
            "Address1"=> "",
            "Address2"=> "",
            "TownCity"=> "",
            "Postcode"=> "",
            "Country"=> "Singapore",
            "CountryCode"=> "SG",
            "Currency"=> "SGD",
            "PaddleSeed"=> null,
            "ApprovalType"=> "Automatic",
            "ApprovalRules"=> [
              "Avs3DSecureRuleEnabled"=> false,
              "AvsRuleEnabled"=> false,
              "Secure3DRuleEnabled"=> false,
              "NotBlockedBidderRuleEnabled"=> false,
              "VerifiedEmailAddressRuleEnabled"=> false,
              "VerifiedTelephoneNumberRuleEnabled"=> false,
              "NotInternationalBidderRuleEnabled"=> false,
              "AllowedCountryCodes"=> ""
            ],
            "ImportantInformation"=> "MC Test",
            "Terms"=> "MC Test",
            "ShippingInfo"=> "<p>PACKING, SHIPPING &amp; INSURANCE</p>\n<p>HotLotz collaborates with professional art handling companies and other specialist shippers to provide cost effective, bespoke packing and domestic and international insured door-to-door shipping.</p>\n<p>We can provide indicative quotes within 24 hours, on request.</p>\n<p>Please contact&nbsp;<a href=\"mailto:hello@hotlotz.com\">hello@hotlotz.com</a>&nbsp;if you would like further information on either service.</p>",
            "TelephoneNumber"=> "+65 6254 7616",
            "Website"=> "www.hotlotz.com",
            "Email"=> "zinaguarnaccia@actiontechnologygroup.com",
            "ConfirmationEmail"=> "maychothet@nexlabs.co",
            "RegistrationEmail"=> "maychothet@nexlabs.co",
            "PaymentReceivedEmail"=> "maychothet@nexlabs.co",
            "RequestConfirmationEmail"=> true,
            "RequestRegistrationEmail"=> true,
            "RequestPaymentReceivedEmail"=> true,
            "IncrementSetName"=> "HotLotz Increment Table",
            "AutomaticDeposit"=> false,
            "AutomaticRefund"=> false,
            "VatRate"=> 20,
            "BuyersPremiumVatRate"=> 0.2,
            "InternetSurchargeVatRate"=> 0.2,
            "BuyersPremium"=> 0,
            "InternetSurchargeRate"=> 5,
            "BuyersPremiumCeiling"=> 0,
            "InternetSurchargeCeiling"=> 0,
            "ImplementationType"=> "Self",
            "WinnersNotificationNote"=> "Test",
            "TimedStart"=> "2020-07-26 08:17:00.000000",
            "TimedFirstLotEnds"=> "2020-07-30 08:17:00.000000",
            "SaleDates"=> [],
            "ViewingDates"=> [
              [
                "Starts"=> "2020-07-26 08:17:00.000000",
                "Ends"=> "2020-07-26 12:17:00.000000"
              ]
            ],
            "AuctionCardTypes"=> [],
            "PieceMeal"=> false,
            "PublishPostSaleResults"=> false,
            "InternationalDebitCardFixedFee"=> 0,
            "InternationalDebitCardPercentageFee"=> 0,
            "InternationalDebitCardFeeExcludedCountryList"=> [],
            "ProjectedSpendRequired"=> false,
            "LinkedAuctions"=> [],
            "AtgCommission"=> 0,
            "AtgCommissionCeiling"=> 0,
            "ClientsAuctionId"=> "",
            "HammerExcess"=> "",
            "HideVenueAddressForLotLocations"=> false,
            "AdvancedTimedBiddingEnabled"=> false
        ];

        try {
            $result = $api->createAuction($auction_object);
            $this->info('Create Auction -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Create Auction -> FAIL');
            \Log::error($e);
        }
    }

    private function getAuctionById(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $auctionId = "0513349e-aeb5-408b-885d-abe500fb23e1";

            $result = $apiInstance->getAuctionById($auctionId);
            $this->info('Get Auctions By Id -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Auctions By Id -> FAIL');
            \Log::error($e);
        }
    }

    private function getAuctionByReference(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getAuctionByReference("HOTZLO10020");
            $this->info('Get Auctions By Reference -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Auctions By Reference -> FAIL');
            \Log::error($e);
        }
    }

    private function getAuctionsByClientId(){

        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $clientId = NHelpers::getGapClientId();
            $result = $apiInstance->getAuctionByClientId($clientId);
            $this->info('Get Auctions By ClientId -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Auctions By ClientId -> FAIL');
            \Log::error($e);
        }

    }

    private function getBiddingHistoryByAuctionId(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {

            $auctionId = "b2417c80-28d4-4449-8ccf-ac2a006feef6";

            $result = $apiInstance->getBiddingHistoryByAuctionId($auctionId);
            $this->info('Get Bidding History by AuctionId -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Bidding History by AuctionId -> FAIL');
            \Log::error($e);
        }
    }

    private function getSupportedCategories(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getSupportedCategoriesByPlatform("SR");
            $this->info('Get Supported Categories -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Supported Categories -> FAIL');
            \Log::error($e);
        }
    }

    private function updateAuction(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        $auction = [
            "AuctionId"=> "0513349e-aeb5-408b-885d-abe500fb23e1",
            "Title"=> "[Test] - Test Auction update by Commandline",
        ];

        try {
            $result = $apiInstance->updateAuction($auction);
            $this->info('Update Auction -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Update Auction  -> FAIL');
            \Log::error($e);
        }
    }
}
