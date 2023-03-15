<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Auction\Http\Repositories\AuctionRepository;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\CheckLot;
use DB;
use Hash;
use Illuminate\Support\Str;
use App\Modules\Customer\Models\Customer;
use App\Events\Client\SaleroomCustomerRegisterEvent;
use App\Modules\Customer\Repositories\CustomerRepository;

class CheckAuction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:checkauction {auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GAP - Check auction by ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $auctionRepository, $customerRepository;
    public function __construct(AuctionRepository $auctionRepository, CustomerRepository $customerRepository)
    {
        parent::__construct();
        $this->auctionRepository = $auctionRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - CheckAuction Command =======');
        \Log::channel('checkAuctionLog')->info('======= Start - CheckAuction Command =======');

        $auction_id = $this->argument('auction_id');
        \Log::channel('checkAuctionLog')->info('auction_id : '.$auction_id);

        $auction = Auction::find($auction_id);

        if($auction && isset($auction->sr_auction_id) && $auction->is_closed != 'Y'){

            \Log::channel('checkAuctionLog')->info('sr_auction_id : '.$auction->sr_auction_id);

            $gap_auction = Auction::getAuctionById($auction->sr_auction_id);

            if($gap_auction['auction_status'] == "AwaitingSubmission" || $gap_auction['auction_status'] == "Submitted" || $gap_auction['auction_status'] == "ChecksInProgress" || $gap_auction['auction_status'] == "ReadyToInvoice" || $gap_auction['auction_status'] == "Invoiced"){

                $bidderlist = null;
                $winnerlist = null;
                $saleresults = null;

                $auction_data = [
                    'status' => $gap_auction['auction_status'],
                    'is_closed' => 'Y',
                    'bidders_list' => $bidderlist,
                    'winners_list' => $winnerlist,
                    'sr_sale_result' => $saleresults,
                ];
                $this->auctionRepository->update($auction_id, $auction_data, true);

                ## Start - New logic [15Dec2021]
                ## Get Winner and insert new Customer if not exist in System.
                $email_count = 0;
                $winners = Auction::getWinnersByAuctionId($auction->sr_auction_id);
                foreach ($winners as $key => $winner) {
                    $buyer_info = Item::getBidderByBidderId($winner['bidder_id']);

                    if( !isset($buyer_info['error']) ){

                        $buyer = Customer::where('email',$buyer_info['email'])->first();
                        if(!$buyer){
                            \Log::channel('checkAuctionLog')->info("New Client [".$buyer_info['email']."]");
                            $country = DB::table('countries')->where('iso_3166_2',$buyer_info['country_code'])->first();
                            $country_of_residence = ($country)?$country->id:702;

                            $countrycode = DB::table('country_codes')->where('country_code',$buyer_info['country_code'])->first();
                            $phone = $buyer_info['phone_number'];
                            $dialling_code = '+65';
                            if( $countrycode ){
                                $dialling_code = $countrycode->dialling_code;
                                $phone = str_replace($dialling_code, "", $phone);
                            }

                            $address1 = $buyer_info['address1'];
                            if( isset($buyer_info['address2']) ){
                                $address1 = $address1. ',' .$buyer_info['address2'];
                            }
                            if( isset($buyer_info['address3']) ){
                                $address1 = $address1. ',' .$buyer_info['address3'];
                            }

                            $cust_data = [];
                            $cust_data['is_active'] = 1;
                            $cust_data['password'] = Hash::make('password');
                            $cust_data['hash_password'] = Hash::make('password');
                            $cust_data['type'] = isset($buyer_info['company'])?'organization':'individual';
                            $cust_data['reg_behalf_company'] = isset($buyer_info['company'])?1:0;
                            $cust_data['company_name'] = $buyer_info['company'];
                            $cust_data['ref_no'] = Customer::getCustomerRefNo();
                            $cust_data['salutation'] = $buyer_info['title'];
                            $cust_data['title'] = $buyer_info['title'];
                            $cust_data['firstname'] = $buyer_info['first_name'];
                            $cust_data['lastname'] = $buyer_info['last_name'];
                            $cust_data['fullname'] = $buyer_info['first_name'].' '.$buyer_info['last_name'];
                            $cust_data['email'] = $buyer_info['email'];
                            $cust_data['address1'] = $address1;
                            $cust_data['address2'] = null;
                            $cust_data['address3'] = null;
                            $cust_data['city'] = $buyer_info['town_city'];
                            $cust_data['county'] = $buyer_info['county_state'] ?? null;
                            $cust_data['state'] = $buyer_info['county_state'] ?? null;
                            $cust_data['postal_code'] = $buyer_info['post_code_zip'];
                            $cust_data['country_of_residence'] = $country_of_residence;
                            $cust_data['dialling_code'] = $dialling_code;
                            $cust_data['phone'] = $phone;
                            ##For SaleroomCustomerRegister Email Link
                            $cust_data['hash_id'] = (string) Str::uuid();

                            \Log::channel('checkAuctionLog')->info('New Customer Data : '.print_r($cust_data,true));
                            $new_cust = Customer::create($cust_data);
                            $this->customerRepository->createCorrespondenceAddress($new_cust, $buyer_info);
                            event( new SaleroomCustomerRegisterEvent($new_cust->id, $auction->title) );

                            \Log::channel('checkAuctionLog')->info("End for this winner [".$buyer_info['email']."]");
                        }
                    }
                    else {
                        \Log::channel('checkAuctionLog')->info('getBidderByBidderId apr returns Error');
                    }
                    $email_count ++;
                }
                \Log::channel('checkAuctionLog')->info('email_count : '.$email_count);
                ##End - New logic [15Dec2021]


                ## Get lots and check item(s) "Sold" or "UnSold"
                $lots = AuctionItem::where('auction_id',$auction_id)->whereNotNull('lot_id')->whereNull('status')->get();
                \Log::channel('checkAuctionLog')->info('lots count : '.count($lots));

                foreach ($lots as $key => $lot) {
                    \Log::channel('checkAuctionLog')->info('called gap:checklot Job');
                    CheckLot::dispatch($lot->lot_id);
                }
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - CheckAuction Command =======');
        \Log::channel('checkAuctionLog')->info('======= End - CheckAuction Command =======');
    }
}
