<?php

namespace App\Console\Commands;

use App\Exceptions\QueueFailReport;
use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\Customer\Models\Customer;
use App\Modules\Auction\Models\Auction;
use App\Modules\EmailTemplate\Models\EmailTemplate;
use App\Events\Xero\XeroAuctionInvoiceEvent;
use App\Events\CustomerCreatedEvent;
use App\Events\ItemLifcycleNextStageChangeEvent;
use App\Events\ItemHistoryEvent;
use App\Helpers\NHelpers;
use DB;
use Hash;
use Illuminate\Support\Str;
use App\Events\Client\SaleroomCustomerRegisterEvent;
use App\Modules\Customer\Repositories\CustomerRepository;

class CheckLot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:checklot {lot_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GAP - Check Lot by ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $itemRepository, $customerRepository;
    public function __construct(ItemRepository $itemRepository, CustomerRepository $customerRepository)
    {
        parent::__construct();
        $this->itemRepository = $itemRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - CheckLot Command =======');
        \Log::channel('checkAuctionLog')->info('======= Start - CheckLot Command =======');

        $lot_id = $this->argument('lot_id');
        \Log::channel('checkAuctionLog')->info('Lot ID : '.$lot_id);

        $auction_item = AuctionItem::where('lot_id',$lot_id)->first();
        \Log::channel('checkAuctionLog')->info('item_id : '.$auction_item->item_id);
        \Log::channel('checkAuctionLog')->info('auction_id : '.$auction_item->auction_id);

        $current_item_lifecycle = ItemLifecycle::where('type','auction')->where('item_id',$auction_item->item_id)->where('reference_id',$auction_item->auction_id)->where('action',ItemLifecycle::_PROCESSING_)->first();
        \Log::channel('checkAuctionLog')->info('current_item_lifecycle ID : '.$current_item_lifecycle->id);


        $today = date('Y-m-d H:i:s');

        $lot = Item::getLot($lot_id);
        if(isset($lot)){
            // $end_time_utc = NHelpers::changeJsonDateTimeToPhpDateTime($lot['end_time_utc']);
            // \Log::channel('checkAuctionLog')->info('end_time_utc : '.$end_time_utc);

            $auction_item_data = [];
            $item_data = [];
            $item_lifecycle_data = [];
            $buyer_id = 0;

            $auction_item_data['is_lot_ended'] = 'Y';
            $auction_item_data['sr_lot_data'] = $lot;

            $sold_price_inclusive_gst = 0;
            $sold_price_exclusive_gst = 0;

            if($lot['sold']){
                \Log::channel('checkAuctionLog')->info('Item_'.$auction_item->item_id.' is sold');

                $sold_price_inclusive_gst = $lot['total_hammer_price'];
                $sold_price_exclusive_gst = ($lot['total_hammer_price'] / 1.08);

                // $bid_placed_date_time_utc = NHelpers::changeJsonDateTimeToPhpDateTime($lot['bid_placed_date_time_utc']);
                // \Log::channel('checkAuctionLog')->info('bid_placed_date_time_utc : '.$bid_placed_date_time_utc);

                $auction = Auction::find($auction_item->auction_id);
                $auction_name = isset($auction)?$auction->title:'';
                $buyer_id = $this->getBuyerId($lot['winning_bidder_id'], $auction_name);
                \Log::channel('checkAuctionLog')->info('buyer_id : '.$buyer_id);

                $auction_item_data['status'] = Item::_SOLD_;
                $auction_item_data['sold_date'] = $today;
                $auction_item_data['sold_price'] = $sold_price_inclusive_gst;
                $auction_item_data['sold_price_inclusive_gst'] = $sold_price_inclusive_gst;
                $auction_item_data['sold_price_exclusive_gst'] = $sold_price_exclusive_gst;
                $auction_item_data['buyer_id'] = $buyer_id;


                $item_data['status'] = Item::_SOLD_;
                $item_data['sold_date'] = $today;
                $item_data['sold_price'] = $sold_price_inclusive_gst;
                $item_data['sold_price_inclusive_gst'] = $sold_price_inclusive_gst;
                $item_data['sold_price_exclusive_gst'] = $sold_price_exclusive_gst;
                $item_data['buyer_id'] = $buyer_id;
                $item_data['storage_date'] = $today;
                $item_data['tag'] = 'in_storage';


                $item_lifecycle_data['status'] = Item::_SOLD_;
                $item_lifecycle_data['action'] = ItemLifecycle::_FINISHED_;
                $item_lifecycle_data['sold_date'] = $today;
                $item_lifecycle_data['sold_price'] = $sold_price_inclusive_gst;
                $item_lifecycle_data['sold_price_inclusive_gst'] = $sold_price_inclusive_gst;
                $item_lifecycle_data['sold_price_exclusive_gst'] = $sold_price_exclusive_gst;
                $item_lifecycle_data['buyer_id'] = $buyer_id;
            }else{

                $auction_item_data['status'] = Item::_UNSOLD_;

                $item_data['status'] = Item::_UNSOLD_;

                $item_lifecycle_data['status'] = Item::_UNSOLD_;
            }

            if(count($item_data)>0){
                $result = $this->itemRepository->update($auction_item->item_id, $item_data, true, 'Lot '.$item_data['status']);
            }

            $updated_item = Item::find($auction_item->item_id);
            \Log::channel('checkAuctionLog')->info('Item_'.$auction_item->item_id.' status : '.$updated_item->status);

            if(count($auction_item_data)>0){
                AuctionItem::where('lot_id',$lot_id)->update($auction_item_data + NHelpers::updated_at_by());
            }

            if(count($item_lifecycle_data)>0){
                ItemLifecycle::where('type','auction')->where('item_id',$auction_item->item_id)->where('reference_id',$auction_item->auction_id)->update($item_lifecycle_data + NHelpers::updated_at_by());

                ## Skip to Storage Stage
                if($item_lifecycle_data['status'] == Item::_SOLD_){
                    $skip_lifecycle = [
                        'action'=>ItemLifecycle::_SKIPPED_,
                    ];
                    ItemLifecycle::where('item_id',$auction_item->item_id)->where('type','!=','storage')->where('reference_id','!=',$auction_item->auction_id)->whereNull('action')->update($skip_lifecycle + NHelpers::updated_at_by());

                    $storage_lifecycle = [
                        'action'=>ItemLifecycle::_PROCESSING_,
                        'entered_date'=>$today,
                    ];
                    ItemLifecycle::where('type','storage')->where('item_id',$auction_item->item_id)->update($storage_lifecycle + NHelpers::updated_at_by());
                }
            }


            if($updated_item->status == Item::_SOLD_){

                ##for Item Sold Noti Email Schedule
                $item_history = [
                    'item_id' => $updated_item->id,
                    'customer_id' => $updated_item->customer_id,
                    'buyer_id' => $buyer_id,
                    'auction_id' => $auction_item->auction_id,
                    'item_lifecycle_id' => $current_item_lifecycle->id,
                    'price' => $current_item_lifecycle->price,
                    'sold_price' => $updated_item->sold_price,
                    'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
                    'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
                    'type' => 'auction',
                    'status' => Item::_SOLD_,
                    'entered_date' => $today,
                    'specific_date' => $today,
                ];
                \Log::channel('lifecycleLog')->info('call ItemHistoryEvent -  Sold item');
                event( new ItemHistoryEvent($item_history) );


                $storage_item_lifecycle = ItemLifecycle::where('type','storage')->where('item_id',$auction_item->item_id)->first();

                ##for Storage Noti Email Schedule when Item Sold
                $storage_item_history = [
                    'item_id' => $updated_item->id,
                    'customer_id' => null,//need to set null for Sold-Storage History
                    'buyer_id' => $buyer_id,
                    'auction_id' => $auction_item->auction_id,
                    'item_lifecycle_id' => $storage_item_lifecycle->id,
                    'price' => $storage_item_lifecycle->price,
                    'type' => 'lifecycle',
                    'status' => Item::_STORAGE_,
                    'entered_date' => $today,
                ];
                \Log::channel('lifecycleLog')->info('call ItemHistoryEvent - enter into storage');
                event( new ItemHistoryEvent($storage_item_history) );

                ## Xero Item Event
                $xero_data = [
                    'type' => 'auction',
                    'hammer_price' => $lot['total_hammer_price'],
                    'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
                    'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
                    'item_id' => $auction_item->item_id,
                    'buyer_id' => $buyer_id,
                    'seller_id' => $updated_item->customer_id,
                    'auction_id' => $auction_item->auction_id,
                ];
                event( new XeroAuctionInvoiceEvent($xero_data) );
            }

            ## Directly Change Lifecycle Next Stage
            if($updated_item->status == Item::_UNSOLD_){
                \Log::channel('checkAuctionLog')->info('Item_'.$auction_item->item_id.' is unsold');

                //for Item Unsold Noti Email Schedule
                $item_history = [
                    'item_id' => $updated_item->id,
                    'customer_id' => $updated_item->customer_id,
                    'auction_id' => $auction_item->auction_id,
                    'item_lifecycle_id' => $current_item_lifecycle->id,
                    'price' => $current_item_lifecycle->price,
                    'type' => 'auction',
                    'status' => Item::_UNSOLD_,
                    'entered_date' => $today,
                ];
                \Log::channel('lifecycleLog')->info('call ItemHistoryEvent -  Unsold item');
                event( new ItemHistoryEvent($item_history) );

                \Log::channel('lifecycleLog')->info('call ItemLifcycleNextStageChangeEvent -  Unsold item');
                event( new ItemLifcycleNextStageChangeEvent($auction_item->item_id, $current_item_lifecycle->id) );
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - CheckLot Command =======');
        \Log::channel('checkAuctionLog')->info('======= End - CheckLot Command =======');
    }

    public function getBuyerId($bidder_id, $auction_name)
    {
        $buyer_id = 0;
        $buyer_info = Item::getBidderByBidderId($bidder_id);

        if( !isset($buyer_info['error']) ){
            $buyer = Customer::where('email',$buyer_info['email'])->first();

            if( $buyer && isset($buyer->id)){
                $buyer_id = $buyer->id;
                $this->customerRepository->createCorrespondenceAddress($buyer, $buyer_info);
            }else{

                \Log::channel('checkAuctionLog')->error("This Client [".$buyer_info['email']."] is not exist in your system");

                // $country = DB::table('countries')->where('iso_3166_2',$buyer_info['country_code'])->first();
                // $country_of_residence = isset($country)?$country->id:702;

                // $countrycode = DB::table('country_codes')->where('country_code',$buyer_info['country_code'])->first();
                // $phone = $buyer_info['phone_number'];
                // $dialling_code = '+65';
                // if( isset($countrycode) ){
                //     $dialling_code = $countrycode->dialling_code;
                //     $phone = str_replace($dialling_code, "", $phone);
                // }

                // $address1 = $buyer_info['address1'];
                // if( isset($buyer_info['address2']) ){
                //     $address1 = $address1. ',' .$buyer_info['address2'];
                // }
                // if( isset($buyer_info['address3']) ){
                //     $address1 = $address1. ',' .$buyer_info['address3'];
                // }

                // $cust_data = [];
                // $cust_data['is_active'] = 1;
                // $cust_data['password'] = Hash::make('password');
                // $cust_data['hash_password'] = Hash::make('password');
                // $cust_data['type'] = isset($buyer_info['company'])?'organization':'individual';
                // $cust_data['reg_behalf_company'] = isset($buyer_info['company'])?1:0;
                // $cust_data['company_name'] = $buyer_info['company'];
                // $cust_data['ref_no'] = Customer::getCustomerRefNo();
                // $cust_data['salutation'] = $buyer_info['title'];
                // $cust_data['title'] = $buyer_info['title'];
                // $cust_data['firstname'] = $buyer_info['first_name'];
                // $cust_data['lastname'] = $buyer_info['last_name'];
                // $cust_data['fullname'] = $buyer_info['first_name'].' '.$buyer_info['last_name'];
                // $cust_data['email'] = $buyer_info['email'];
                // $cust_data['address1'] = $address1;
                // $cust_data['address2'] = null;
                // $cust_data['address3'] = null;
                // $cust_data['city'] = $buyer_info['town_city'];
                // $cust_data['county'] = $buyer_info['county_state'] ?? null;
                // $cust_data['state'] = $buyer_info['county_state'] ?? null;
                // $cust_data['postal_code'] = $buyer_info['post_code_zip'];
                // $cust_data['country_of_residence'] = $country_of_residence;
                // $cust_data['dialling_code'] = $dialling_code;
                // $cust_data['phone'] = $phone;
                // ##For SaleroomCustomerRegister Email Link
                // $cust_data['hash_id'] = (string) Str::uuid();

                // $new_cust = Customer::create($cust_data);
                // $buyer_id = $new_cust->id;

                // $this->customerRepository->createCorrespondenceAddress($new_cust, $buyer_info);

                // event( new SaleroomCustomerRegisterEvent($new_cust->id, $auction_name) );
            }
        }else{
            \Log::channel('checkAuctionLog')->error("getBidderByBidderId API Error ");
        }

        return $buyer_id;
    }
}
