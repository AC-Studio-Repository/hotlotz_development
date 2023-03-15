<?php

namespace App\Console\Commands\Auction;

use DB;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Customer\Models\Customer;
use App\Events\Client\SaleroomCustomerRegisterEvent;

class CreateSrCustomerAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:create_sr_customer_account {bidder_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - CreateSrCustomerAccount Command =======');
        \Log::info('======= Start - CreateSrCustomerAccount Command =======');

        $bidder_id = $this->argument('bidder_id');
        \Log::info('bidder_id : '.$bidder_id);

        $buyer_id = 0;
        $buyer_info = Item::getBidderByBidderId($bidder_id);

        if( !isset($buyer_info['error']) ){
            $buyer_info['email'] = 'maycho'.rand(100,10000).'@gmail.com';
            $buyer = Customer::where('email',$buyer_info['email'])->first();

            if( isset($buyer) ){
                $buyer_id = $buyer->id;
                \Log::info('buyer_id : '.$buyer_id);

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
                $cust_data['address1'] = $buyer_info['address1'];
                $cust_data['address2'] = $buyer_info['address2'];
                $cust_data['address3'] = $buyer_info['address3'];
                $cust_data['city'] = $buyer_info['town_city'];
                $cust_data['county'] = $buyer_info['county_state'];
                $cust_data['postal_code'] = $buyer_info['post_code_zip'];
                $cust_data['country_of_residence'] = $country_of_residence;
                $cust_data['dialling_code'] = $dialling_code;
                $cust_data['phone'] = $phone;
                ##For SaleroomCustomerRegister Email Link
                $cust_data['hash_id'] = (string) Str::uuid();

                $new_cust = Customer::create($cust_data);
                $buyer_id = $new_cust->id;
                \Log::info('buyer_id : '.$buyer_id);

                $auction_name = "Asian Ceramics & Works of Art from the Collection of Quek Kiok Lee (Part I)";
                event( new SaleroomCustomerRegisterEvent($new_cust->id, $auction_name) );
            }
        }else{
            \Log::error("getBidderByBidderId API Error ");
        }
        

        $this->info(date('Y-m-d H:i:s').' ======= End - CreateSrCustomerAccount Command =======');
        \Log::info('======= End - CreateSrCustomerAccount Command =======');
    }
}
