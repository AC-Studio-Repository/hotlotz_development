<?php

namespace App\Modules\Item\Models;

use Hash;
use App\Traits\UUID;
use App\XeroErrorLog;
use App\Helpers\NHelpers;
use App\Models\Lifecycle;
use GAP\Api\LotsApi as LotsApi;
use App\Events\ItemCreatedEvent;
use App\Events\ItemUpdatedEvent;
use Illuminate\Support\Facades\DB;
use GAP\Api\BidderApi as BidderApi;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Xero\Models\XeroInvoice;
use App\Modules\Category\Models\Category;
use App\Modules\Customer\Models\Customer;
use App\Events\ItemCataloguingNeededEvent;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\ItemFeeStructure;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Category\Models\CategoryProperty;
use App\Modules\OrderSummary\Models\OrderSummary;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use App\Modules\Customer\Models\CustomerMarketplaceItem;

class Item extends Model
{
    use SoftDeletes, UUID;

    public $table = 'items';

    public $incrementing = false;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'category_data' => 'array',
    ];

    protected $keyType = 'string';
    
    protected $dispatchesEvents = [
        // 'created' => ItemCreatedEvent::class,
        // 'updated' => ItemUpdatedEvent::class,
    ];

    // Consignment status added to item overview (Pending, In Auction, In Marketplace, Sold, Settled, Paid, Withdrawn, Storage, To Be Collected, or Dispatched)
    const _SWU_ = "SWU";
    const _PENDING_ = "Pending";
    const _PENDING_IN_AUCTION_ = "Pending In Auction";
    const _DECLINED_ = "Declined";
    const _IN_AUCTION_ = "In Auction";
    const _IN_MARKETPLACE_ = "In Marketplace";
    const _SOLD_ = "Sold";
    const _UNSOLD_ = "UnSold";
    const _PAID_ = "Paid";
    const _SETTLED_ = "Settled";
    const _WITHDRAWN_ = "Withdrawn";
    const _STORAGE_ = "Storage";
    const _TO_BE_COLLECTED_ = "To Be Collected";
    const _DISPATCHED_ = "Dispatched";
    const _ITEM_RETURNED_ = "Item Returned";//for Credit Note Item

    // const _REQUEST_PI_ = "Request Physical Item";
    // const _RECEIVED_PI_ = "Received Physical Item";

    //statuses for items table
    // const _DRAFT_ = "Draft"; //Request Physical Item
    // const _PENDING_ = "Pending"; //Received Physical Item
    // const _READY_FOR_LIFECYCLE_ = "Ready for Lifecycle";
    const _LIFECYCLE_ = "Lifecycle";
    // const _DISPATCHED_ = "To be collected or dispatched";
    // const _SETTLED_ = "Settled";
    // const _PENDING_IN_STORAGE_ = "Pending in Storage";
    // const _WITHDRAWN_ = "Withdrawn";
    // const _INACTIVE_ = "Inactive";

    //Statuses for items table when item's lifecycle is started. (items.lifecycle_status)
    const _PENDING_FOR_AUCTION_ = "Pending for Auction"; //Pending when between Auction1 and Auction2
    const _AUCTION_ = "Auction";
    const _MARKETPLACE_ = "Marketplace";
    const _CLEARANCE_ = "Clearance";
    // const _STORAGE_ = "Storage";
    const _PRIVATE_SALE_ = "Private Sale";

    //Status for Cataloguing Needed
    const _COMPLETED_ = "Completed";

    //Status for Approved Item by Approver(Admin User)
    const _APPROVED_ = "Approved";


    //Statuses for item_lifecycles->action
    const _PROCESSING_ = "Processing";
    const _FINISHED_ = "Finished";


    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Customer::class, 'buyer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function lifecycle()
    {
        return $this->belongsTo(Lifecycle::class, 'lifecycle_id');
    }

    public function itemlifecycles()
    {
        return $this->hasMany(ItemLifecycle::class, 'item_id');
    }

    public function itemimages()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    public function fee_structure()
    {
        return $this->hasOne(ItemFeeStructure::class, 'item_id');
    }

    public function customerInvoiceItems()
    {
        return $this->hasMany(CustomerInvoiceItem::class, 'item_id');
    }

    public function customerMarketPlaceItems()
    {
        return $this->hasMany(CustomerMarketplaceItem::class, 'item_id');
    }

    public function xeroInvoice()
    {
        return $this->hasOne(XeroInvoice::class, 'item_id');
    }

    protected function getName()
    {
        return $this->name;
    }

    protected function getNameById($id)
    {
        $item = Item::find($id);
        return $item->name;
    }

    protected function getItemFeeStructureSettings($item, $item_fee)
    {
        $performance_commission_setting = false;
        $minimum_commission_setting = true;
        if ($item->fee_type == 'sales_commission' && $item_fee) {
            if ($item_fee->performance_commission_setting == 1) {
                $performance_commission_setting = true;
            } elseif ($item_fee->performance_commission_setting == 0) {
                $performance_commission_setting = false;
            }

            if ($item_fee->minimum_commission_setting == 1) {
                $minimum_commission_setting = true;
            } elseif ($item_fee->minimum_commission_setting == 0) {
                $minimum_commission_setting = false;
            }
        }

        $insurance_fee_setting = true;
        $listing_fee_setting = false;
        $unsold_fee_setting = false;
        $withdrawal_fee_setting = true;
        $ic_details = true;
        if (($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && isset($item_fee)) {
            if ($item_fee->insurance_fee_setting == 1) {
                $insurance_fee_setting = true;
            } elseif ($item_fee->insurance_fee_setting == 0) {
                $insurance_fee_setting = false;
            }

            if ($item_fee->listing_fee_setting == 1) {
                $listing_fee_setting = true;
            } elseif ($item_fee->listing_fee_setting == 0) {
                $listing_fee_setting = false;
            }

            if ($item_fee->unsold_fee_setting == 1) {
                $unsold_fee_setting = true;
            } elseif ($item_fee->unsold_fee_setting == 0) {
                $unsold_fee_setting = false;
            }

            if ($item_fee->withdrawal_fee_setting == 1) {
                $withdrawal_fee_setting = true;
            } elseif ($item_fee->withdrawal_fee_setting == 0) {
                $withdrawal_fee_setting = false;
            }

            if ($item_fee->ic_details == 1) {
                $ic_details = true;
            } elseif ($item_fee->ic_details == 0) {
                $ic_details = false;
            }
        }

        $item_fee_settings = [
            'performance_commission_setting' => $performance_commission_setting,
            'minimum_commission_setting' => $minimum_commission_setting,
            'insurance_fee_setting' => $insurance_fee_setting,
            'listing_fee_setting' => $listing_fee_setting,
            'unsold_fee_setting' => $unsold_fee_setting,
            'withdrawal_fee_setting' => $withdrawal_fee_setting,
            'ic_details' => $ic_details,
        ];

        return $item_fee_settings;
    }

    protected function getItemImageData($id)
    {
        try {
            $item_images = ItemImage::where('item_id', $id)->get();

            $item_initialpreview = [];
            $item_initialpreviewconfig = [];
            $hide_item_image_ids = '';
            foreach ($item_images as $key => $item_image) {
                $item_initialpreview[] = $item_image->full_path;
                $item_initialpreviewconfig[] = ['caption'=>$item_image->file_name, 'size'=>'57071', 'width'=>"263px", 'height'=>"217px", 'url'=>'/manage/items/'.$item_image->id.'/image_delete', 'key'=>$key, 'extra' => ['_token'=>csrf_token(),'id'=>$item_image->id]];
                $hide_item_image_ids .= $item_image->id . ',';
            }

            return array(
                'item_initialpreview'=>$item_initialpreview,
                'item_initialpreviewconfig'=>$item_initialpreviewconfig,
                'hide_item_image_ids'=>$hide_item_image_ids,
            );
        } catch (\Exception $e) {
            return ['error'=>$e->getMessage()];
        }
    }

    protected function getItemVideoData($id)
    {
        try {
            $item_videos = ItemVideo::where('item_id', $id)->get();

            $item_video_initialpreview = [];
            $item_video_initialpreviewconfig = [];
            $hide_item_video_ids = '';
            foreach ($item_videos as $key => $item_video) {
                $item_video_initialpreview[] = $item_video->full_path;
                $item_video_initialpreviewconfig[] = ['type'=>"video",'filetype'=>"video/mp4",'caption'=>$item_video->file_name, 'url'=>'/manage/items/'.$item_video->id.'/video_delete', 'key'=>$key, 'extra' => ['_token'=>csrf_token(),'id'=>$item_video->id]];
                $hide_item_video_ids .= $item_video->id . ',';
            }

            $data = [
                'item_video_initialpreview'=>$item_video_initialpreview,
                'item_video_initialpreviewconfig'=>$item_video_initialpreviewconfig,
                'hide_item_video_ids'=>$hide_item_video_ids,
            ];
            // dd($data);

            return $data;
        } catch (\Exception $e) {
            return ['error'=>$e->getMessage()];
        }
    }

    protected function getItemInternalPhotoData($id)
    {
        try {
            $item_internal_photos = ItemInternalPhoto::where('item_id', $id)->get();

            $internal_photo_initialpreview = [];
            $internal_photo_initialpreviewconfig = [];
            $hide_item_internal_photo_ids = '';
            foreach ($item_internal_photos as $key => $item_internal_photo) {
                $internal_photo_initialpreview[] = $item_internal_photo->full_path;
                $internal_photo_initialpreviewconfig[] = ['caption'=>$item_internal_photo->file_name, 'size'=>'57071', 'width'=>"263px", 'height'=>"217px", 'url'=>'/manage/items/'.$item_internal_photo->id.'/internal_photo_delete', 'key'=>$key, 'extra' => ['_token'=>csrf_token(),'id'=>$item_internal_photo->id]];
                $hide_item_internal_photo_ids .= $item_internal_photo->id . ',';
            }

            $data = [
                'internal_photo_initialpreview'=>$internal_photo_initialpreview,
                'internal_photo_initialpreviewconfig'=>$internal_photo_initialpreviewconfig,
                'hide_item_internal_photo_ids'=>$hide_item_internal_photo_ids,
            ];
            // dd($data);

            return $data;
        } catch (\Exception $e) {
            return ['error'=>$e->getMessage()];
        }
    }

    public static function generateItemCode($customer_id)
    {
        $item_code = "";
        $item_code_id = 1;

        \Log::info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);
        if ($customer != null) {
            $cust_ref_no = $customer->ref_no;

            \Log::info('cust_ref_no : '.$cust_ref_no);

            ##New Logic for ItemCode
            $item = Item::where('customer_id', $customer_id)->orderBy('item_code_id', 'desc')->first();

            $item_code = $cust_ref_no.'/'."1";
            if ($item != null) {
                $item_code_id = $item->item_code_id + 1; // increment
                $item_code = $cust_ref_no.'/'.$item_code_id;
            }
        }

        $itemcode_data = [
            'item_code'=>$item_code,
            'item_code_id'=>$item_code_id,
        ];

        return $itemcode_data;
    }

    public static function addZero($number, $add_nol)
    {
        while (strlen($number) < $add_nol) {
            $number = "0".$number;
        }
        return $number;
    }

    ## Start - GAP APIs
    public static function createLot($item, $auction_id, $sr_auction_id, $lot_number)
    {
        $opening_price = ($item->is_reserve == 'Y' && isset($item->reserve))?$item->reserve:0;

        $item_lifecycle = ItemLifecycle::where('item_id', $item->id)
                ->where('reference_id', $auction_id)
                ->where('type', 'auction')
                ->first();

        if (isset($item_lifecycle)) {
            $opening_price = $item_lifecycle->price;
        }
        \Log::channel('gapLog')->info('opening_price : '.$opening_price);


        ### Get Sequence Number
        $auctionitem = AuctionItem::whereNull('deleted_at')->where('item_id', $item->id)->where('auction_id', $auction_id)->first();

        $sequence_number = "";
        if (isset($auctionitem)) {
            $sequence_number = ($auctionitem->sequence_number!=null)?$auctionitem->sequence_number:"";
        }
        \Log::channel('gapLog')->info('sequence_number : '.$sequence_number);

        $category_name = Item::getCategoryForGAP($item->category_id);
        \Log::channel('gapLog')->info('category_name : '.$category_name);

        $description = Item::getDescriptionForGAP($item);
        \Log::channel('gapLog')->info('description : '.$description);

        $buyers_premium_percent = 0;
        $auction = Auction::find($auction_id);
        if(isset($auction)){
            $buyers_premium_percent = $auction->buyers_premium;
        }
        \Log::channel('gapLog')->info('buyers_premium_percent : '.$buyers_premium_percent);

        $new_lot = [
            "LotNumber"=> $lot_number, //*
            "LongDescription"=> $description, //*
            "Title"=> $item->name, //*
            "AuctionId"=> $sr_auction_id, //*
            "EndTimeUtc"=> "", //2019-12-31 08:17:00.000000
            // "EndTimeUtc"=> isset($item->end_time_utc)?NHelpers::getGmtDateTime($item->end_time_utc):"", //2019-12-31 08:17:00.000000
            "Quantity"=> 1, //*
            "LowEstimate"=> isset($item->low_estimate)?$item->low_estimate:0,
            "HighEstimate"=> isset($item->high_estimate)?$item->high_estimate:0,
            "Reserve"=> $opening_price,
            "OpeningPrice"=> $opening_price,
            "VATTaxRate"=> 0,
            "BuyersPremiumVatRate"=> (isset($auction) && $auction->is_gst == 'Y')? 0.07 : 0,
            "BuyersPremiumPercent"=> $buyers_premium_percent,
            "BuyersPremiumCeiling"=> 0,
            "InternetSurchargeVatRate"=> 0,
            "InternetSurchargePercent"=> 0,
            "InternetSurchargeCeiling"=> 0,
            "Increment"=> "",
            "CategoryName"=> $category_name,
            "SaleSection"=> isset($item->sub_category)? $item->sub_category:"",
            "IsBulk"=> false, //*
            "ArtistResaleRights"=> false, //*
            "SequenceNumber"=> $sequence_number,
            "IsPotentiallyOffensive"=> false,
            "BuyItNowPrice"=> isset($item->buy_it_now_price)?$item->buy_it_now_price:"",
            "Address1"=> (isset($item->address1) && $item->address1 != '')?$item->address1:"",//"95 Cowley Avenue",
            "Address2"=> (isset($item->address2) && $item->address2 != '')?$item->address2:"",
            "Address3"=> (isset($item->address3) && $item->address3 != '')?$item->address3:"",
            "Address4"=> (isset($item->address4) && $item->address4 != '')?$item->address4:"",
            "TownCity"=> (isset($item->town_city) && $item->town_city != '')?$item->town_city:"",//"Singapore",
            "Postcode"=> (isset($item->postcode) && $item->postcode != '')?$item->postcode:"",//"2342343",
            "CountryCode"=> (isset($item->country_code) && $item->country_code != '')?$item->country_code:"SG",
            "CountyState"=> (isset($item->county_state) && $item->county_state != '')?$item->county_state:"",
            "ShippingInfo"=> "",
        ];

        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->createLot($new_lot);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->createLot: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function updateLot($item, $auction_id, $sr_auction_id, $lot_id, $lot_number)
    {
        $opening_price = ($item->is_reserve == 'Y' && isset($item->reserve))?$item->reserve:0;

        $item_lifecycle = ItemLifecycle::where('item_id', $item->id)
                ->where('reference_id', $auction_id)
                ->where('type', 'auction')
                ->first();

        if (isset($item_lifecycle)) {
            $opening_price = $item_lifecycle->price;
        }
        \Log::channel('gapLog')->info('opening_price : '.$opening_price);


        ### Get Sequence Number
        $auctionitem = AuctionItem::whereNull('deleted_at')->where('item_id', $item->id)->where('auction_id', $auction_id)->first();

        $sequence_number = $lot_number;
        \Log::channel('gapLog')->info('sequence_number : '.$sequence_number);

        $category_name = Item::getCategoryForGAP($item->category_id);
        \Log::channel('gapLog')->info('category_name : '.$category_name);

        $description = Item::getDescriptionForGAP($item);
        \Log::channel('gapLog')->info('description : '.$description);

        $buyers_premium_percent = 0;
        $auction = Auction::find($auction_id);
        if(isset($auction)){
            $buyers_premium_percent = $auction->buyers_premium;
        }
        \Log::channel('gapLog')->info('buyers_premium_percent : '.$buyers_premium_percent);

        $update_lot = [
            "LotId"=> $lot_id, //*
            "LotNumber"=> $lot_number, //*
            "LongDescription"=> $description, //*
            "Title"=> $item->name, //*
            "AuctionId"=> $sr_auction_id, //*
            "EndTimeUtc"=> "", //2019-12-31 08:17:00.000000
            "Quantity"=> 1, //*
            "LowEstimate"=> isset($item->low_estimate)?$item->low_estimate:0,
            "HighEstimate"=> isset($item->high_estimate)?$item->high_estimate:0,
            "Reserve"=> $opening_price,
            "OpeningPrice"=> $opening_price,
            "VATTaxRate"=> 0,
            "BuyersPremiumVatRate"=> (isset($auction) && $auction->is_gst == 'Y')? 0.07 : 0,
            "BuyersPremiumPercent"=> $buyers_premium_percent,
            "BuyersPremiumCeiling"=> 0,
            "InternetSurchargeVatRate"=> 0,
            "InternetSurchargePercent"=> 0,
            "InternetSurchargeCeiling"=> 0,
            "Increment"=> "",
            "CategoryName"=> $category_name,
            "SaleSection"=> isset($item->sub_category)? $item->sub_category:"",
            "IsBulk"=> false, //*
            "ArtistResaleRights"=> false, //*
            "SequenceNumber"=> $sequence_number,
            "IsPotentiallyOffensive"=> false,
            "BuyItNowPrice"=> isset($item->buy_it_now_price)?$item->buy_it_now_price:"",
            "Address1"=> (isset($item->address1) && $item->address1 != '')?$item->address1:"",//"95 Cowley Avenue",
            "Address2"=> (isset($item->address2) && $item->address2 != '')?$item->address2:"",
            "Address3"=> (isset($item->address3) && $item->address3 != '')?$item->address3:"",
            "Address4"=> (isset($item->address4) && $item->address4 != '')?$item->address4:"",
            "TownCity"=> (isset($item->town_city) && $item->town_city != '')?$item->town_city:"",//"Singapore",
            "Postcode"=> (isset($item->postcode) && $item->postcode != '')?$item->postcode:"",//"2342343",
            "CountryCode"=> (isset($item->country_code) && $item->country_code != '')?$item->country_code:"SG",
            "CountyState"=> (isset($item->county_state) && $item->county_state != '')?$item->county_state:"",
            "ShippingInfo"=> ""
        ];

        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->updateLot($update_lot);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->updateLot: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function updateLots($lots)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->updateLots($lots);
            return $result;
        } catch (\Exception $e) {
            \Log::info('ERROR : LotsApi->updateLots : '. $e->getMessage());
            return ['error'=>'Exception when calling LotsApi->updateLots: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function getLotsByAuctionId($sr_auction_id)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getLotsByAuctionId($sr_auction_id);
            return $result;
        } catch (\Exception $e) {
            \Log::info('ERROR : LotsApi->getLotsByAuctionId : '. $e->getMessage());
            return ['error'=>'Exception when calling LotsApi->getLotsByAuctionId: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function addImageUrlToLot($image_data)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->addImageUrlToLot($image_data);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->addImageUrlToLot: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function addBase64ImageToLot($image_data)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->addBase64ImageToLot($image_data);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->addBase64ImageToLot: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function deleteLot($lot_id)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->deleteLot($lot_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->deleteLot: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function removeLotImage($lot_image_id)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->removeLotImage($lot_image_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->removeLotImage: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function getLot($lot_id)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getLot($lot_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->getLot: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function getSaleResultByAuctionId($auction_id)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getSaleResultByAuctionId($auction_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->getSaleResultByAuctionId: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function getBidderByBidderId($bidder_id)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new BidderApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getBidderByBidderId($bidder_id);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling BidderApi->getBidderByBidderId: '. $e->getMessage()." ". PHP_EOL];
        }
    }

    public static function setVideosInLot($video_data)
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        // $video_data = [
        //     "LotId"=> "87fbdbbe-9c74-4b66-9e8c-ac4c0057a17a",
        //     "VideoUrls"=> [
        //         "https://www.youtube.com/watch?v=s7MesM2adD8",
        //         "https://www.facebook.com/watch/?v=786999878533483"
        //     ]
        // ];

        try {
            $result = $apiInstance->setVideosInLot($video_data);
            return $result;
        } catch (\Exception $e) {
            return ['error'=>'Exception when calling LotsApi->setVideosInLot: '. $e->getMessage()." ". PHP_EOL];
        }
    }
    ## End - GAP APIs

    public static function getPurchaseDetails($item)
    {
        $item_purchase = [];
        if (isset($item->buyer) && $item->buyer_id>0 && in_array($item->status, [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_,Item::_DISPATCHED_])) {
            $item_purchase['auction_or_marketplace'] = $item->lifecycle_status;
            $item_purchase['collection_delivery_status'] = null;
            if (in_array($item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])) {
                $item_purchase['collection_delivery_status'] = Item::_TO_BE_COLLECTED_;
            }
            if ($item->status == Item::_DISPATCHED_ || $item->tag == 'dispatched') {
                $item_purchase['collection_delivery_status'] = Item::_DISPATCHED_;
            }

            $customer = Customer::find($item->buyer_id);
            if (isset($customer)) {
                $item_purchase['buyer_number'] = $customer['ref_no'];
                $item_purchase['buyer_name'] = $customer['firstname'].' '.$customer['lastname'];
                $item_purchase['buyer_tel_no'] = $customer['phone'];
                $item_purchase['buyer_email'] = $customer['email'];
                $item_purchase['buyer_address'] = $customer['address1'].', '.$customer['address2'];
                $item_purchase['buyer_city'] = $customer['city'];
                $item_purchase['buyer_county'] = $customer['county'];
                $item_purchase['buyer_postcode'] = $customer['postal_code'];
            }
        }

        return $item_purchase;
    }

    public function getXeroProductIdAttribute()
    {
        if (is_null($this->xero_item_id)) {
            $accountingAutomate = resolve('App\Modules\Xero\Accounting\Automate');

            $item = $accountingAutomate->getItem($this->item_number);
            return $item->getItems()[0]->getItemId();
        }

        return $this->xero_item_id;
    }

    public static function checkLifecycleStatus($itemlifecycle)
    {
        $lifecycle_status = null;
        $content = '';

        if ($itemlifecycle->type == 'auction') {
            $lifecycle_status = Item::_AUCTION_;
            $auction = Auction::where('id', '=', $itemlifecycle->reference_id)->select('title', 'timed_start', 'timed_first_lot_ends')->first();
            $content = 'Your "'.$itemlifecycle->item_name.'_'.$itemlifecycle->item_number.'" is located in "'.$auction->title.'" Auction.';
        }
        if ($itemlifecycle->type == 'marketplace') {
            $lifecycle_status = Item::_MARKETPLACE_;
            $content = 'Your "'.$itemlifecycle->item_name.'_'.$itemlifecycle->item_number.'" is located in "'.$lifecycle_status.'".';
        }
        if ($itemlifecycle->type == 'clearance') {
            $lifecycle_status = Item::_CLEARANCE_;
            $content = 'Your "'.$itemlifecycle->item_name.'_'.$itemlifecycle->item_number.'" is located in "'.$lifecycle_status.'".';
        }
        if ($itemlifecycle->type == 'privatesale') {
            $lifecycle_status = Item::_PRIVATE_SALE_;
            $content = 'Your "'.$itemlifecycle->item_name.'_'.$itemlifecycle->item_number.'" is located in "'.$lifecycle_status.'".';
        }
        if ($itemlifecycle->type == 'storage') {
            $lifecycle_status = Item::_STORAGE_;
            $content = 'Your "'.$itemlifecycle->item_name.'_'.$itemlifecycle->item_number.'" is located in "'.$lifecycle_status.'". A storage fee of $'.$itemlifecycle->price.' per day will be charged on your item after a '.$itemlifecycle->second_period.' days period.';
        }

        return ['lifecycle_status'=>$lifecycle_status, 'content'=>$content];
    }

    public static function addOtherValueForCategoryProperty($category_id, $key, $attachment)
    {
        $subcategory = CategoryProperty::where('category_id', $category_id)->where('key', $key)->first();

        $substr = 'Other';
        $new_value = str_replace($substr, $attachment.$substr, $subcategory->value);
        // dd($new_value);

        $subcategory->value = $new_value;
        $subcategory->save();
    }

    public static function packData($request, $item_code_arr, $action)
    {
        $payload = [];
        if ($action == 'create') {
            $payload['registration_date'] = date('Y-m-d H:i:s');
            $payload['status'] = Item::_PENDING_;
            $payload['permission_to_sell'] = 'N';
            $payload['cataloguing_needed'] = 'Y';
            $payload['is_cataloguing_approved'] = 'N';
            $payload['is_valuation_approved'] = 'N';
            $payload['is_fee_structure_approved'] = 'N';
            $payload['is_fee_structure_needed'] = 'Y';
            $payload['fee_type'] = 'sales_commission';
        }
        if ($action == 'update' && isset($request->status)) {
            $payload['status'] = $request->status;
        }

        $category_data = [];
        if ($request->count_cat_property > 0) {
            for ($i=0; $i < $request->count_cat_property; $i++) {
                $key = 'key_'.$i;
                $value = 'value_'.$i;
                $other_value = 'value_'.$i.'_other';

                // echo $key . "<br>";
                $category_data[$request->$key] = $request->$value;

                if (isset($request->$other_value)) {
                    $attachment = $request->$other_value.',';
                    Item::addOtherValueForCategoryProperty($request->category_id, $request->$key, $attachment);

                    $category_data[$request->$key] = $request->$other_value;
                }

                if (is_array($request->$value)) {
                    $arr_value = implode(",", $request->$value);

                    if (isset($request->$other_value)) {
                        $substr = 'Other';
                        $arr_value = str_replace($substr, $request->$other_value, $arr_value);
                    }

                    $category_data[$request->$key] = $arr_value;
                }
            }
        }
        // dd($category_data);

        $payload['cataloguer_id'] = $request->cataloguer_id; //*
        $payload['name'] = $request->name; //*
        $payload['title'] = $request->name; //*
        $payload['customer_id'] = $request->customer_id; //*

        $payload['is_new'] = isset($request->is_new)?'Y':'N';
        $payload['is_tree_planted'] = isset($request->is_tree_planted)?'Y':'N';
        $payload['is_highlight'] = isset($request->is_highlight)?'Y':'N';
        $payload['brand'] = $request->brand ?? null;

        $payload['item_number'] = $item_code_arr['item_code'];
        $payload['item_code_id'] = $item_code_arr['item_code_id'];
        $payload['location'] = $request->location;
        $payload['long_description'] = $request->long_description ?? null;

        $payload['category_id'] = $request->category_id;
        $payload['sub_category'] = $request->sub_category ?? null;
        if (isset($request->sub_category) && $request->sub_category == 'Other') {
            $payload['sub_category'] = $request->sub_category_other ?? null;
        }
        $payload['is_pspm'] = isset($request->is_pspm)?'Y':'N';

        $payload['condition'] = $request->condition ?? null;
        $payload['specific_condition_value'] = $request->specific_condition_value ?? null;
        $payload['provenance'] = $request->provenance ?? null;
        $payload['designation'] = $request->designation ?? null;

        $payload['is_dimension'] = isset($request->is_dimension)?'Y':'N';
        $payload['dimensions'] = $request->dimensions ?? null;

        $payload['is_weight'] = isset($request->is_weight)?'Y':'N';
        $payload['weight'] = $request->weight ?? null;

        $payload['additional_notes'] = $request->additional_notes ?? null;
        $payload['internal_notes'] = $request->internal_notes ?? null;

        $payload['category_data'] = $category_data;
        $payload['is_pro_photo_need'] = isset($request->is_pro_photo_need)?'Y':'N';

        return $payload;
    }

    public static function getItemLifecycle($item_id)
    {
        $item_lifecycles = ItemLifecycle::where('item_id', $item_id)
                        ->whereNull('deleted_at')
                        ->select('id', 'item_id', 'type', 'price', 'period', 'second_period', 'is_indefinite_period', 'reference_id', 'action', 'status', 'entered_date', 'sold_date', 'withdrawn_date')
                        ->get();

        return $item_lifecycles;
    }

    public static function getCategoryForGAP($category_id)
    {
        $gap_category = "";

        $category = Category::find($category_id);

        #Collaborations
        if ($category_id == 13 || $category->name == 'Collaborations') {
            $gap_category = '';
        }

        #Fine Art => Art
        if ($category_id == 2 || $category->name == 'Art') {
            $gap_category = 'Fine Art';
        }
        #Asian Art => Asian Collectables
        if ($category_id == 3 || $category->name == 'Asian Collectables') {
            $gap_category = 'Asian Art';
        }

        #Collectables
        // Maps & Bonds
        // Wine & Spirits
        if ($category_id == 1 || $category->name == 'Maps & Bonds' || $category_id == 11 || $category->name == 'Wine & Spirits') {
            $gap_category = 'Collectables';
        }

        #Vintage Fashion & Textiles
        // Designer Fashion
        if ($category_id == 4 || $category->name == 'Designer Fashion') {
            $gap_category = 'Vintage Fashion & Textiles';
        }

        #Furniture
        // Furniture
        // Rugs & Carpets
        if ($category_id == 5 || $category->name == 'Furniture' || $category_id == 8 || $category->name == 'Rugs & Carpets') {
            $gap_category = 'Furniture';
        }

        #Clocks, Watches & Jewellery
        // Jewellery
        // Watches
        if ($category_id == 6 || $category->name == 'Jewellery' || $category_id == 10 || $category->name == 'Watches') {
            $gap_category = 'Clocks, Watches & Jewellery';
        }

        #Decorative Art
        // Decorative Arts
        // Tableware
        // Home Décor
        if ($category_id == 12 || $category->name == 'Decorative Arts' || $category_id == 9 || $category->name == 'Tableware' || $category_id == 7 || $category->name == 'Home Decor') {
            $gap_category = 'Decorative Art';
        }

        return $gap_category;
    }

    public static function getDescriptionForGAP($item)
    {
        $description = isset($item->title)?($item->title):'';

        $description .= isset($item->designation)?('<br />'.PHP_EOL.''.$item->designation):'';

        if ($description == '') {
            $description .= nl2br($item->long_description);
        } else {
            $description .= '<br />'.PHP_EOL.''.nl2br($item->long_description);
        }

        $description .= isset($item->brand)?('<br />'.PHP_EOL.''.$item->brand):'';
        $description .= isset($item->dimensions)?('<br />'.PHP_EOL.''.$item->dimensions):'';
        $description .= isset($item->weight)?('<br />'.PHP_EOL.''.$item->weight):'';
        $description .= isset($item->provenance)?('<br />'.PHP_EOL.'Provenance: '.$item->provenance):'';

        $condition = '';
        // if (isset($item->condition) && $item->condition == 'no_condition') {
        //     $condition = '<br />'.PHP_EOL.'Condition: '.'No obvious condition issues';
        // }
        // if (isset($item->condition) && $item->condition == 'minor_signs') {
        //     $condition = '<br />'.PHP_EOL.'Condition: '.'Minor signs of wear commensurate with age and use';
        // }
        // if (isset($item->condition) && $item->condition == 'specific_condition' && ($item->specific_condition_value != null && $item->specific_condition_value != '')) {
        //     $condition = '<br />'.PHP_EOL.'Condition: '.$item->specific_condition_value;
        // }
        if ($item->condition != null) {
            $condition = '<br />'.PHP_EOL.'Condition: '.Item::getConditionValue($item->condition);
            if($item->condition == 'specific_condition' || $item->condition == 'general_condition') {
                $condition = '<br />'.PHP_EOL.'Condition: '.nl2br($item->specific_condition_value);
            }
        }
        $description .= $condition;

        $description .= isset($item->additional_notes)?('<br />'.PHP_EOL.'Additional Information: '.nl2br($item->additional_notes)):'';

        return $description;
    }

    public static function updateRelatedItemStatus($item, $status = null, $byWhomId = null, $type = null, $auction_id = null)
    {
        $date = date('Y-m-d H:i:s');

        $item_data = [
            'buyer_id' => $byWhomId,
            'status' => $status,
        ];
        if($status == Item::_PAID_){
            $item_data['paid_date'] = $date;
        }
        if($status == Item::_SETTLED_){
            $item_data['settled_date'] = $date;
        }
        Item::where('id', $item->id)->update($item_data);

        if ($type == 'auction' && $auction_id != null) {
            ItemLifecycle::where('item_id', $item->id)->where('type', $type)->where('reference_id', $auction_id)->update(
                [
                    'buyer_id' => $byWhomId,
                    'status' => $status,
                ]
            );

            AuctionItem::where('item_id', $item->id)->where('auction_id', $auction_id)->update(
                [
                    'buyer_id' => $byWhomId,
                    'status' => $status,
                ]
            );
        }

        // if ($type != 'auction') {
        //     ItemLifecycle::where('item_id', $item->id)->where('type', $type)->whereIn('status', [Item::_SOLD_, Item::_PAID_])->update(
        //         [
        //             'buyer_id' => $byWhomId,
        //             'status' => $status,
        //         ]
        //     );
        // }
    }

    /**
     * Get the item's final price.
     *
     * @return float
     */
    public function getFinalPriceAttribute()
    {
        if ($this->xeroInvoice) {
            return $this->xeroInvoice->price;
        } else {
            return $this->sold_price;
        }
    }

    public function orderSummaries()
    {
        return $this->belongsToMany(OrderSummary::class)
                ->withTimestamps();
    }

    public function getSearchNameAttribute()
    {
        return $this->name . ' / ('. $this->item_number . ')';
    }

    public static function getConditionList($condition = null, $category_id = null)
    {
        // $old_conditions = [
        //     'no_condition' => 'No obvious condition issues',
        //     'minor_signs' => 'Minor signs of wear commensurate with age and use',
        //     'specific_condition' => 'Specific condition',
        //     "ask_for_condition" => "For a condition report or further images, please contact the saleroom via hello@hotlotz.com",
        // ];

        // if($category_id == 4){
        //     $old_conditions = [
        //         'new_with_original_packaging' => Item::getConditionValue('new_with_original_packaging'),
        //         'new' => Item::getConditionValue('new'),
        //         'pristine' => Item::getConditionValue('pristine'),
        //         'good' => Item::getConditionValue('good'),
        //         'vintage' => Item::getConditionValue('vintage'),
        //     ];
        // }

        $new_conditions = [
            'general_condition' => 'General Condition',
            'specific_condition' => 'Specific Condition',
        ];
        if($condition != null){
            $new_conditions[$condition] = Item::getConditionValue($condition);
        }

        return $new_conditions;
    }

    public static function getConditionValue($type)
    {
        $conditions = [
            'no_condition' => 'No obvious condition issues',
            'minor_signs' => 'Minor signs of wear commensurate with age and use',
            'specific_condition' => 'Specific Condition',
            "ask_for_condition" => "For a condition report or further images, please contact the saleroom via hello@hotlotz.com",
            'new_with_original_packaging' => "NEW (with original packaging) - Items have never been worn and are as good as brand new. They have their original packaging and/or tags",
            'new' => 'NEW - Items have never been worn and are as good as brand new',
            'pristine' => 'PRISTINE - Items have been worn once or twice but are still in excellent condition',
            'good' => 'GOOD - Items have been worn but still look great. Check catalogue notes',
            'vintage' => 'FAIR - Condition is commensurate with age and use. Some imperfections exist. Check catalogue notes. Viewing is recommended',
            'general_condition' => 'General Condition',
        ];

        $condition_value = $conditions[$type] ?? '';

        return $condition_value;
    }

    public static function getConditionSolution($condition)
    {
        $solution = "";
        if($condition == 'general_condition'){
            $solution = "For a condition report or further images please email hello@hotlotz.com at least 48 hours prior to the closing date of the auction.".PHP_EOL.' '.PHP_EOL."This is an auction of preowned and antique items. Many items are of an age or nature which precludes their being in perfect condition and you should expect general wear and tear commensurate with age and use. We strongly advise you to examine items before you bid.".PHP_EOL.' '.PHP_EOL."Condition reports are provided as a goodwill gesture and are our general assessment of damage and restoration.   Whilst care is taken in their drafting, they are for guidance only. We will not be held responsible for oversights concerning damage or restoration.";
        }
        if($condition == 'specific_condition'){
            $solution = "Condition Report".PHP_EOL."[INSERT TEXT]".PHP_EOL.' '.PHP_EOL."This is an auction of preowned and antique items. Many items are of an age or nature which precludes their being in perfect condition and you should expect general wear and tear commensurate with age and use. We strongly advise you to examine items before you bid.".PHP_EOL.' '.PHP_EOL."Condition reports are provided as a goodwill gesture and are our general assessment of damage and restoration.   Whilst care is taken in their drafting, they are for guidance only. We will not be held responsible for oversights concerning damage or restoration.";
        }
        return $solution;
    }

    public static function getLocation()
    {
        return ['Saleroom'=>'Saleroom', 'With Client'=>'With Client', 'See Internal Notes'=>'See Internal Notes'];
    }

    public function xeroErrorLogs()
    {
        return $this->hasMany(XeroErrorLog::class, 'item_id');
    }
}
