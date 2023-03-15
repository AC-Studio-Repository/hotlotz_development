<?php

namespace App\Jobs;

use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\Item;
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

class CheckAuction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $auctionRepository, $customerRepository;
    protected $auction_id;
    public function __construct($auction_id)
    {
        $this->auction_id = $auction_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AuctionRepository $auctionRepository, CustomerRepository $customerRepository)
    {
        \Log::channel('checkAuctionLog')->info('======= Start - CheckAuction Job =======');

        $auction_id = $this->auction_id;
        \Log::channel('checkAuctionLog')->info("auction_id : ".$auction_id);

        try {
            $auction = Auction::find($auction_id);

            if (!isset($auction) || $auction == null || $auction->is_closed == 'Y') {
                $this->job->delete();
                \Log::channel('checkAuctionLog')->info("Auctiion_".$auction_id." Job is deleted");
            } else {

                $gap_auction = Auction::getAuctionById($auction->sr_auction_id);

                if ($gap_auction['auction_status'] == "AwaitingSubmission" || $gap_auction['auction_status'] == "Submitted" || $gap_auction['auction_status'] == "ChecksInProgress" || $gap_auction['auction_status'] == "ReadyToInvoice" || $gap_auction['auction_status'] == "Invoiced") {
                    
                    $bidderlist = null;
                    $winnerlist = null;
                    $saleresults = null;

                    // $bidder_list = Auction::getBiddersByAuctionId($auction->sr_auction_id);
                    // if (!isset($bidder_list['error'])) {
                    //     $bidderlist = $bidder_list;
                    // }

                    // $winner_list = Auction::getWinnersByAuctionId($auction->sr_auction_id);
                    // if (!isset($winner_list['error'])) {
                    //     $winnerlist = $winner_list;
                    // }

                    // $sale_results = Item::getSaleResultByAuctionId($auction->sr_auction_id);
                    // if (!isset($sale_results['error'])) {
                    //     $saleresults = $sale_results;
                    // }

                    $auction_data = [
                        'status' => $gap_auction['auction_status'],
                        'is_closed' => 'Y',
                        'bidders_list' => $bidderlist,
                        'winners_list' => $winnerlist,
                        'sr_sale_result' => $saleresults,
                    ];
                    $auctionRepository->update($auction->id, $auction_data, true);


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

                                $new_cust = Customer::create($cust_data);
                                $customerRepository->createCorrespondenceAddress($new_cust, $buyer_info);
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
                    $lots = AuctionItem::where('auction_id', $auction->id)->whereNotNull('lot_id')->whereNull('status')->get();
                    \Log::channel('checkAuctionLog')->info('lots count : '.count($lots));

                    foreach ($lots as $key => $lot) {
                        \Log::channel('checkAuctionLog')->info('dispatch CheckLot Job');
                        CheckLot::dispatch($lot->lot_id);
                    }

                } else {
                    if ($this->attempts() > $this->tries) {
                        if (date('Y-m-d H:i:s') >= $auction->timed_first_lot_ends) {
                            \Log::channel('checkAuctionLog')->info('extended 10 minutes');
                            $this->release(600);
                        } else {
                            \Log::channel('checkAuctionLog')->info("extended auction's endtime + 10 minutes");
                            $datetime = new \Carbon\Carbon($auction->timed_first_lot_ends);
                            $this->release($datetime->addMinutes(10));
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('checkAuctionLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::channel('checkAuctionLog')->info('======= End - CheckAuction Job =======');
    }

    public function failed(\Exception $exception)
    {
        \Log::channel('checkAuctionLog')->error('======= Failed - CheckAuction Job '. $this->auction_id .'=======');
    }
}
