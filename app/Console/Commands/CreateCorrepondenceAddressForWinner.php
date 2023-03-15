<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Repositories\CustomerRepository;

class CreateCorrepondenceAddressForWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:create_correpondence_address_for_winner {auction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Correspondence Address for Winner by Auction ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $customerRepository;
    public function __construct(CustomerRepository $customerRepository)
    {
        parent::__construct();
        $this->customerRepository = $customerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - CreateCorrepondenceAddressForWinner =======');
        \Log::info('======= Start - CreateCorrepondenceAddressForWinner =======');

        try{

            $auction_id = $this->argument('auction_id');
            \Log::info('Auction ID : '.$auction_id);

            $auction = Auction::find($auction_id);

            if(isset($auction) && $auction->sr_auction_id != null){
                $winners = Auction::getWinnersByAuctionId($auction->sr_auction_id);
                \Log::info('Winner list : '.count($winners));

                foreach ($winners as $key => $winner) {
                    \Log::info('Bidder ID : '.$winner['bidder_id']);
                    $buyer_id = 0;
                    $buyer_info = Item::getBidderByBidderId($winner['bidder_id']);

                    if( !isset($buyer_info['error']) ){
                        $buyer = Customer::where('email',$buyer_info['email'])->first();

                        if( isset($buyer) ){
                            $buyer_id = $buyer->id;
                            $this->customerRepository->createCorrespondenceAddress($buyer, $buyer_info);
                        }else{
                            $country = DB::table('countries')->where('iso_3166_2',$buyer_info['country_code'])->first();
                            $country_of_residence = isset($country)?$country->id:702;

                            $countrycode = DB::table('country_codes')->where('country_code',$buyer_info['country_code'])->first();
                            $phone = $buyer_info['phone_number'];
                            $dialling_code = '+65';
                            if( isset($countrycode) ){
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
                            $buyer_id = $new_cust->id;

                            $this->customerRepository->createCorrespondenceAddress($new_cust, $buyer_info);

                        }
                    }else{
                        \Log::error("getBidderByBidderId API Error ");
                    }
                }

            }

        } catch (\Exception $e) {
            $this->error("ERROR - CreateCorrepondenceAddressForWinner - " . $e->getMessage());
            \Log::error("ERROR - CreateCorrepondenceAddressForWinner - " . $e->getMessage());
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - CreateCorrepondenceAddressForWinner =======');
        \Log::info('======= End - CreateCorrepondenceAddressForWinner =======');
    }
}
