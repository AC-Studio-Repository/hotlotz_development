<?php

namespace App\Modules\Auction\Models;

use App\Traits\UUID;
use App\Helpers\NHelpers;
use App\Events\AuctionCreatedEvent;
use App\Events\AuctionUpdatedEvent;
use GAP\Api\BidderApi as BidderApi;
use GAP\Api\AuctionApi as AuctionApi;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Xero\Models\XeroInvoice;
use App\Modules\Item\Models\ItemLifecycle;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Customer\Models\CustomerInvoice;
use Illuminate\Support\Facades\Redis;

class Auction extends Model
{
    //
    use SoftDeletes,UUID;

    public $table = 'auctions';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'sale_dates' => 'array',
        'viewing_dates' => 'array',
        'auction_card_types' => 'array',
        'international_debit_card_fee_excluded_country_list' => 'array',
        'linked_auctions' => 'array',
        'auction_listings' => 'array',
        // 'bidders_list' => 'array',
        // 'winners_list' => 'array',
        // 'sr_sale_result' => 'array',
        'lot_bids' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => AuctionCreatedEvent::class,
        // 'updated' => AuctionUpdatedEvent::class,
    ];

    public function invoices()
    {
        return $this->hasMany(CustomerInvoice::class, 'invoice_id');
    }

    public function xeroInvoices()
    {
        return $this->hasMany(XeroInvoice::class, 'auction_id');
    }

    public function getTitle()
    {
        return $this->title;
    }

    ## Start - GAP APIs
    public static function getSaleroomCategory()
    {
        $config = NHelpers::getGapConfig();

        $api = new AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );
        $platform_code = 'SR'; // string |

        try {
            $result = $api->getSupportedCategoriesByPlatform($platform_code);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling AuctionApi->getSupportedCategoriesByPlatform: '. $e->getMessage(). PHP_EOL];
        }
    }

    public static function createAuction($auction)
    {
        $config = NHelpers::getGapConfig();

        $api = new AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        $auction_object = [
            "LegacyId"=> 0,
            "AuctionCreatedDateTimeUtc"=> date('Y-m-d H:i:s'),
            "TimeIsAlreadyUtc"=> false,
            "CreatedByUser"=> "",
            "Title"=> $auction->title,
            "ClientId"=> NHelpers::getGapClientId(),
            "AuctionListings"=> $auction->auction_listings,
            "TimezoneId"=> "Singapore Standard Time",
            "CardRequired"=> false,
            "Address1"=> "120 Lower Delta Road, #01-15",
            "TownCity"=> "Singapore",
            "Postcode"=> "169208",
            "Country"=> "Singapore",
            "CountryCode"=> "SG",
            "Currency"=> "SGD",
            "PaddleSeed"=> null,
            "ApprovalType"=> "Automatic",
            "ApprovalRules"=> [
                "Avs3DSecureRuleEnabled"=> false,
                "AvsRuleEnabled"=> false,
                "Secure3DRuleEnabled"=> false,
                "NotBlockedBidderRuleEnabled"=> true,
                "VerifiedEmailAddressRuleEnabled"=> false,
                "VerifiedTelephoneNumberRuleEnabled"=> false,
                "NotInternationalBidderRuleEnabled"=> false,
                "AllowedCountryCodes"=> ""
            ],
            "ImportantInformation"=> "",//$auction->important_information,
            "Terms"=> "",//$auction->terms,
            "ShippingInfo"=> "",//"<p>PACKING, SHIPPING &amp; INSURANCE</p>\n<p>HotLotz collaborates with professional art handling companies and other specialist shippers to provide cost effective, bespoke packing and domestic and international insured door-to-door shipping.</p>\n<p>We can provide indicative quotes within 24 hours, on request.</p>\n<p>Please contact&nbsp;<a href=\"mailto:hello@hotlotz.com\">hello@hotlotz.com</a>&nbsp;if you would like further information on either service.</p>",
            "TelephoneNumber"=> "+65 62547616",//$auction->telephone_number,
            "Website"=> "https://hotlotz.com",//$auction->website,
            "Email"=> "hello@hotlotz.com",//$auction->email,
            "ConfirmationEmail"=> $auction->confirmation_email,
            "RegistrationEmail"=> $auction->registration_email,
            "PaymentReceivedEmail"=> $auction->payment_receive_email,
            "RequestConfirmationEmail"=> false,
            "RequestRegistrationEmail"=> false,
            "RequestPaymentReceivedEmail"=> false,
            "IncrementSetName"=> "HotLotz Increment Table",
            "AutomaticDeposit"=> false,
            "AutomaticRefund"=> false,
            "VatRate"=> 0,//($auction->is_gst == 'Y')? 7 : 0, command out (25Jan2021)
            "BuyersPremiumVatRate"=> ($auction->is_gst == 'Y')? 0.07 : 0,
            "InternetSurchargeVatRate"=> 0,
            // "BuyersPremium"=> $auction->buyers_premium, Change logic (30Aug2021)
            // "BuyersPremium"=> ($auction->is_gst == 'Y')? 22.43 : $auction->buyers_premium, //change by Sophy [23Nov21]
            "BuyersPremium"=> $auction->buyers_premium ?? 24, //change by Sophy [23Nov21]
            "InternetSurchargeRate"=> 0,
            "BuyersPremiumCeiling"=> 0,
            "InternetSurchargeCeiling"=> 0,
            "ImplementationType"=> "Self",
            "WinnersNotificationNote"=> "",//Test
            "TimedStart"=> $auction->timed_start,
            "TimedFirstLotEnds"=> $auction->timed_first_lot_ends,
            "SaleDates"=> [],
            "ViewingDates"=> [
                // [
                //     "Starts"=> $auction->timed_start,
                //     "Ends"=> $auction->timed_start
                // ]
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
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling AuctionApi->createAuction: '. $e->getMessage(). PHP_EOL];
        }
    }

    public static function updateAuction($auction)
    {
        $config = NHelpers::getGapConfig();

        $api = new AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        $auction_object = [
            "AuctionId"=> $auction->sr_auction_id,
            "TimeIsAlreadyUtc"=> false,
            "Title"=> $auction->title,
            "ClientId"=> NHelpers::getGapClientId(),
            "AuctionListings"=> $auction->auction_listings,
            "TimezoneId"=> "Singapore Standard Time",
            "CardRequired"=> false,
            "Address1"=> "120 Lower Delta Road, #01-15",
            "TownCity"=> "Singapore",
            "Postcode"=> "169208",
            "Country"=> "Singapore",
            "CountryCode"=> "SG",
            "Currency"=> "SGD",
            "PaddleSeed"=> null,
            "ApprovalType"=> "Automatic",
            "ApprovalRules"=> [
                "Avs3DSecureRuleEnabled"=> false,
                "AvsRuleEnabled"=> false,
                "Secure3DRuleEnabled"=> false,
                "NotBlockedBidderRuleEnabled"=> true,
                "VerifiedEmailAddressRuleEnabled"=> false,
                "VerifiedTelephoneNumberRuleEnabled"=> false,
                "NotInternationalBidderRuleEnabled"=> false,
                "AllowedCountryCodes"=> ""
            ],
            "ImportantInformation"=> "",//$auction->important_information,
            "Terms"=> "",//$auction->terms,
            "ShippingInfo"=> "",//"<p>PACKING, SHIPPING &amp; INSURANCE</p>\n<p>HotLotz collaborates with professional art handling companies and other specialist shippers to provide cost effective, bespoke packing and domestic and international insured door-to-door shipping.</p>\n<p>We can provide indicative quotes within 24 hours, on request.</p>\n<p>Please contact&nbsp;<a href=\"mailto:hello@hotlotz.com\">hello@hotlotz.com</a>&nbsp;if you would like further information on either service.</p>",
            "TelephoneNumber"=> "+65 62547616",//$auction->telephone_number,
            "Website"=> "https://hotlotz.com",//$auction->website,
            "Email"=> "hello@hotlotz.com",//$auction->email,
            "ConfirmationEmail"=> $auction->confirmation_email,
            "RegistrationEmail"=> $auction->registration_email,
            "PaymentReceivedEmail"=> $auction->payment_receive_email,
            "RequestConfirmationEmail"=> false,
            "RequestRegistrationEmail"=> false,
            "RequestPaymentReceivedEmail"=> false,
            "IncrementSetName"=> "HotLotz Increment Table",
            "AutomaticDeposit"=> false,
            "AutomaticRefund"=> false,
            "VatRate"=> 0,//($auction->is_gst == 'Y')? 7 : 0, command out (25Jan2021)
            "BuyersPremiumVatRate"=> ($auction->is_gst == 'Y')? 0.07 : 0,
            "InternetSurchargeVatRate"=> 0,
            // "BuyersPremium"=> $auction->buyers_premium, Change logic (30Aug2021)
            // "BuyersPremium"=> ($auction->is_gst == 'Y')? 22.43 : $auction->buyers_premium, //change by Sophy [23Nov21]
            "BuyersPremium"=> $auction->buyers_premium ?? 24, //change by Sophy [23Nov21]
            "InternetSurchargeRate"=> 0,
            "BuyersPremiumCeiling"=> 0,
            "InternetSurchargeCeiling"=> 0,
            "ImplementationType"=> "Self",
            "WinnersNotificationNote"=> "",//Test
            "TimedStart"=> $auction->timed_start,
            "TimedFirstLotEnds"=> $auction->timed_first_lot_ends,
            "SaleDates"=> [],
            "ViewingDates"=> [
                // [
                //     "Starts"=> $auction->timed_start,
                //     "Ends"=> $auction->timed_start
                // ]
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
        // dd($auction_object);

        try {
            $result = $api->updateAuction($auction_object);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling AuctionApi->updateAuction: '. $e->getMessage(). PHP_EOL];
        }
    }

    public static function publishAuction($auction_id)
    {
        $config = NHelpers::getGapConfig();

        $api = new AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $api->publishAuction($auction_id);
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling AuctionApi->publishAuction: '. $e->getMessage(). PHP_EOL];
        }
    }

    public static function getAuctionById($auction_id)
    {
        $config = NHelpers::getGapConfig();

        $api = new AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $api->getAuctionById($auction_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling AuctionApi->getAuctionById: '. $e->getMessage(). PHP_EOL];
        }
    }

    public static function getBiddingHistoryByAuctionId($sr_auction_id)
    {
        $config = NHelpers::getGapConfig();

        $api = new AuctionApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $api->getBiddingHistoryByAuctionId($sr_auction_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling BidderApi->getBiddingHistoryByAuctionId: '. $e->getMessage(). PHP_EOL];
        }
    }

    public static function getBiddersByAuctionId($sr_auction_id)
    {
        $config = NHelpers::getGapConfig();

        $api = new BidderApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $api->getBiddersByAuctionId($sr_auction_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling BidderApi->getBiddersByAuctionId: '. $e->getMessage(). PHP_EOL];
        }
    }

    public static function getWinnersByAuctionId($sr_auction_id)
    {
        $config = NHelpers::getGapConfig();

        $api = new BidderApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $api->getWinnersByAuctionId($sr_auction_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling BidderApi->getWinnersByAuctionId: '. $e->getMessage(). PHP_EOL];
        }
    }
    ## End - GAP APIs



    public static function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public static function packData($request)
    {
        $payload['legeacy_id'] = 0;
        $payload['auction_created_date_time_utc'] = '';
        $payload['time_is_already_utc'] = false;
        $payload['created_by_user'] = '';
        $payload['type'] = $request->type;//*
        $payload['title'] = $request->title;//*
        $payload['status'] = 'Awaiting approval';
        $payload['sr_category_name'] = $request->sr_category_name;//*
        $payload['client_id'] = '';//set with auth api
        $payload['auction_listings'] = [
            "PlatformCode" => "SR", //to known platform code
            "AuctionTypeAndListing" => $request->type, //Timed/Live
            "CategoryName" => $request->sr_category_name, //use exact name of primary category of the auction
            "Private" => true //true false
        ];
        $payload['timezone_id'] = 'Singapore Standard Time';//GMT Standard Time
        $payload['card_required'] = false; //default false
        $payload['address1'] = "120 Lower Delta Road, #01-15";//$request->address1; //*
        $payload['town_city'] = "Singapore";//$request->town_city; //*
        $payload['country_state_name'] = null;//$request->country_state_name; //optional *
        $payload['post_code'] = "169208";//$request->post_code; //*
        $payload['country'] = "Singapore";//$request->country; //optional *
        $payload['country_code'] = "SG";//$request->country_code; //selected *
        $payload['currency'] = 'SGD';//$request->currency; //selected *
        $payload['paddle_seed'] = null; //nullable
        $payload['approval_type'] = 'Automatic'; //Automatic/Manual
        $payload['important_information'] = "";//$request->important_information; //*
        $payload['terms'] = "";//$request->terms;//*
        $payload['shipping_info'] = '';//$request->shipping_info; //*
        $payload['telephone_number'] = "+65 62547616";//$request->telephone_number; //*
        $payload['website'] = "https://hotlotz.com";//$request->website; *
        $payload['email'] = "hello@hotlotz.com";//$request->email; //*
        $payload['confirmation_email'] = $request->confirmation_email; //same as emal optionl *
        $payload['registration_email'] = $request->registration_email; //same as emal optionl *
        $payload['payment_receive_email'] = $request->payment_receive_email; //same as emal optionl *
        $payload['increment_set_name'] = "HotLotz Increment Table";//$request->increment_set_name; //default 10s *
        $payload['minimum_deposite'] = 0;//$request->minimum_deposite; //default 0 set if true automatic deposit */////
        $payload['automatic_deposite'] = 0;//$request->automatic_deposite; //default false *
        $payload['automatic_refund'] = 0;//$request->automatic_refund; //default false
        $payload['vat_rate'] = 0;//($request->is_gst == 'Y')? 0.07 : 0; //default 20
        $payload['buyers_premium_vat_rate'] = 0;//($request->is_gst == 'Y')? 0.07 : 0; //default 0.2
        $payload['internet_surcharge_vat_rate'] = 0; //default 0.2
        $payload['buyers_premium'] = $request->buyers_premium; //default 0
        $payload['internet_surcharge_rate'] = 0; //default 5
        $payload['winner_notification_note'] = '';//$request->winner_notification_note; //nullable *
        $payload['sale_dates'] = [];
        $payload['viewing_dates'] = [];
        $payload['auction_card_types'] = [];
        $payload['piece_meal'] = false; //default false
        $payload['publish_post_sale_results'] = false; //default false
        $payload['international_debit_card_fixed_fee'] = 0; //default 0
        $payload['international_debit_card_percentage_fee'] = 0; //default 0
        $payload['international_debit_card_fee_excluded_country_list'] = []; //default []
        $payload['projected_spend_required'] = false; //default false
        $payload['linked_auctions'] = []; //default [] if auctions linke add auction id like { "1234x-24ks2-lsda2-msdk2-232131", "23kke-21421-21421k-123-" }
        $payload['atg_commission'] = 0; //default 0
        $payload['atg_commission_ceiling'] = 0; //default 0
        $payload['clients_auction_id'] = ""; //nullable
        $payload['hammer_excess'] = ""; //nullable
        $payload['hide_venue_address_for_lot_locations'] = 0;//$request->hide_venue_address_for_lot_locations; //default false *
        $payload['advanced_time_bidding_enabled'] = false; //default false

        // $payload['timed_start'] =  NHelpers::formatDateTime($request->timed_start);
        // $payload['timed_first_lot_ends'] = NHelpers::formatDateTime($request->timed_first_lot_ends); //date *
        $payload['timed_start'] = date("Y-m-d H:i:s", strtotime($request->timed_start));
        $payload['timed_first_lot_ends'] = date("Y-m-d H:i:s", strtotime($request->timed_first_lot_ends));

        if ($payload['automatic_deposite'] == false) {
            $payload['minimum_deposite'] = '0';
        }

        // $payload['sr_auction_data'] = [];
        // $payload['bidders_list'] = [];
        // $payload['winners_list'] = [];
        $payload['viewing_date_start'] = $request->viewing_date_start ? $request->viewing_date_start : null;
        $payload['viewing_date_end'] = $request->viewing_date_end ? $request->viewing_date_end : null;
        $payload['auction_detail'] = $request->auction_detail ?? null;
        $payload['consignment_deadline'] = $request->consignment_deadline ? date("Y-m-d", strtotime($request->consignment_deadline)) : null;
        $payload['consignment_info'] = $request->consignment_info ?? null;
        $payload['coming_auction_about'] = $request->coming_auction_about ?? null;
        $payload['coming_auction_tick'] = $request->coming_auction_tick ?? 0;
        // for GAP AuctionCreate API
        $payload['is_gst'] = $request->is_gst ?? null;
        $payload['sale_type'] = $request->sale_type ?? null;

        return $payload;
    }

    public static function getAuctionResultByType($auction_id, $type)
    {
        $auction = Auction::find($auction_id);

        $serachInRedis = Redis::get(':auction_result:'.date('ymd').':'.$auction_id.':'.$type);

        if($serachInRedis){
            return $serachInRedis;
        }

        $today = date('Y-m-d');
        $lot_ends_date = date('Y-m-d', strtotime('+2 day', strtotime($auction->timed_first_lot_ends) ));

        if($auction->is_closed == 'Y' && $auction->$type != null && $lot_ends_date <= $today){
            return $auction->$type;
        }else{

            $lots = AuctionItem::where('auction_id', $auction_id)
                ->join('items','items.id','auction_items.item_id')
                ->whereNotNull('auction_items.lot_id')
                // ->where('items.status','!=',Item::_DECLINED_)
                ->whereNull('items.deleted_at');

            if($type == 'total_lots'){
                $$type = $lots->count();
            }

            if($type == 'total_bids'){
                $$type = 0;
                if($auction && $auction->sr_auction_id != null && $auction->is_closed == 'Y'){
                    // $bidding_histories = Auction::getBiddingHistoryByAuctionId($auction->sr_auction_id);
                    // if( !isset($bidding_histories['error']) ) {
                    //     $$type = count($bidding_histories);
                    // }

                    $sale_results = Item::getSaleResultByAuctionId($auction->sr_auction_id);
                    $total_bids = 0;
                    $hammer_total = 0;
                    $lots_sold = 0;
                    $lot_bids = [];
                    if( !isset($sale_results['error']) ) {
                        foreach ($sale_results as $key => $value) {
                            $total_bids = $total_bids + $value['number_of_bids'];
                            ## Number of Bids for Each Lot
                            AuctionItem::where('auction_id',$auction_id)->where('lot_id',$value['lot_id'])->update(['number_of_bids'=>$value['number_of_bids']]);

                            $hammer_total = $hammer_total + $value['total_hammer_price'];
                            // dd($total_hammer_price);

                            if($value['lot_status'] == 'Sold'){
                                $lots_sold = $lots_sold + 1;
                            }

                            $lot_bids[$value['lot_id']] = $value['number_of_bids'];
                        }
                        if(count($lot_bids) > 0){
                            Auction::where('id',$auction_id)->update(['lot_bids'=>$lot_bids]);
                        }
                    }
                    $$type = $total_bids;

                    $auction->hammer_total = $hammer_total;
                    $auction->lots_sold = $lots_sold;
                    $auction->save();
                }
            }

            if($type == 'high_estimate' || $type == 'low_estimate'){
                //$items = Item::whereIn('id', $lots->pluck('auction_items.item_id'))->get();
                //$$type = $items->sum($type) ?? 0;
                $item_ids = $lots->pluck('auction_items.item_id');

                $total_low_estimate = 0;
                $total_high_estimate = 0;

                foreach ($item_ids as $key => $item_id) {
                    $itemlifecycle = ItemLifecycle::where('item_id',$item_id)
                        ->where('reference_id',$auction_id)
                        ->where('type','auction')
                        ->first();

                    $itemDetail = Item::find($item_id);

                    $is_exist_in_first_auction = 'no';
                    if($itemlifecycle != null && $itemDetail != null && in_array($itemDetail->status,[Item::_SWU_, Item::_PENDING_, Item::_PENDING_IN_AUCTION_, Item::_IN_AUCTION_]) ){

                        $checkItemlifecycle = ItemLifecycle::where('item_id',$item_id)
                            ->where('reference_id','!=',$auction_id)
                            ->where('id','<',$itemlifecycle->id)
                            ->where('type','auction')
                            ->first();

                        if($checkItemlifecycle != null){
                            $first_auction = Auction::find($checkItemlifecycle->reference_id);
                            if($first_auction != null && $first_auction->is_closed != 'Y'){
                                $is_exist_in_first_auction = 'yes';
                            }
                        }
                    }

                    $is_sold_in_first_auction = 'no';
                    if($itemDetail != null && (in_array($itemDetail->status,[Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) || ($itemDetail->status == Item::_DISPATCHED_ || $itemDetail->tag == 'dispatched'))) {

                        $checkItemlifecycle = ItemLifecycle::where('item_id',$item_id)
                            ->where('reference_id','!=',$auction_id)
                            ->whereIn('status',[Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])
                            ->where('type','auction')
                            ->first();

                        if($checkItemlifecycle != null){
                            $is_sold_in_first_auction = 'yes';
                        }
                    }

                    if($itemDetail != null && $is_sold_in_first_auction != 'yes' && $is_exist_in_first_auction != 'yes'){
                        $total_low_estimate = $total_low_estimate + (float)$itemDetail->low_estimate;
                        $total_high_estimate = $total_high_estimate + (float)$itemDetail->high_estimate;
                    }
                }
                if($type == 'low_estimate'){
                    $$type = $total_low_estimate;
                }
                if($type == 'high_estimate'){
                    $$type = $total_high_estimate;
                }

            }

            if($type == 'hammer_total'){
                // $$type = $lots->whereNotNull('auction_items.sold_price')->select('auction_items.item_id','auction_items.sold_price')->sum('auction_items.sold_price') ?? 0;

                $$type = 0;
                if($auction->hammer_total > 0){
                    $$type = $auction->hammer_total;
                }
            }

            if($type == 'lots_sold'){
                // $$type = $lots->whereNotNull('auction_items.sold_price')->count();

                $$type = 0;
                if($auction->lots_sold > 0){
                    $$type = $auction->lots_sold;
                }
            }

            if($type == 'percentage_sold'){
                $lot_total = $lots->count();
                // $lot_sold = $lots->whereNotNull('auction_items.sold_price')->count();

                // if ($lot_total > 0) {
                //     $$type = round(($lot_sold/$lot_total)*100, 2);
                // } else {
                //     $$type = 0;
                // }

                $$type = 0;
                if($lot_total > 0 && $auction->lots_sold > 0){
                    $$type = round(($auction->lots_sold/$lot_total)*100, 2);
                }
            }

            if($auction->is_closed == 'Y'){
                $auction->$type = $$type;
                $auction->save();
            }else{
                Redis::set(':auction_result:'.date('ymd').':'.$auction_id.':'.$type, $$type, 'EX', 86400);
            }
            return $$type;
        }
    }

    public static function publish_invoice($auction_id, $local)
    {
        return CustomerInvoice::where('auction_id', $auction_id)
            ->where('type','invoice')
            ->where('active', 0)
            ->whereHas('customer', function($q) use ($local){
                if($local == 'local'){
                    $q->where('country_of_residence', 702);
                }else{
                    $q->where('country_of_residence', '!=', 702);
                }
            })->count();
    }

    public static function publish_bill($auction_id)
    {
        return CustomerInvoice::where('auction_id', $auction_id)->where('type','bill')->where('active', 0)->count();
    }

    public static function getSaleTypes()
    {
        $types = [
            "home_n_decor" => "Home & Decor",
            "jewellery_n_watches" => "Jewellery & Watches",
            "art" => "Art",
            "asian_ceramics_n_works_of_art" => "Asian Ceramics & Works of Art",
            "designer_n_luxury_fashion" => "Designer & Luxury Fashion",
            "single_owner_collections" => "Single Owner Collections",
            "home_contents" => "Home Contents",
            "others" => "Others",
        ];
        return $types;
    }
}
