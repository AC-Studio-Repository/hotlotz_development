<?php

namespace App\Modules\Customer\Repositories;

use DB;
use App\User;
use App\Models\Country;
use App\Helpers\NHelpers;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Models\CustomerNote;

class CustomerRepository
{
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function create($payload)
    {
        return $this->customer->create($payload);
    }

    public function update($id, $payload, $withTrash = false)
    {
        return $this->customer
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function getCutomerAddress($customer_id)
    {
        $result = DB::table('customer_addresses')
                ->where('customer_addresses.customer_id', '=', $customer_id)
                ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                ->orderBy('customer_addresses.address_id', 'desc')
                ->get();

        $address = [];
        if(!$result->isEmpty()) {
            foreach ($result as $key => $value) {
                $country = Country::where('id', '=', $value->country_id)->first();
                $address[] = [
                    'address_id' => $value->address_id,
                    'country_id' => $value->country_id,
                    'country_name' => $country->name,
                    'postalcode' => $value->postalcode,
                    'city' => $value->city,
                    'type' => $value->type,
                    'address' => $value->address,
                    'address2' => $value->address2,
                    'firstname' => $value->firstname,
                    'lastname' => $value->lastname,
                    'state' => $value->state,
                    'zip' => $value->zip_code,
                    'phone' => $value->daytime_phone,
                    'address_nickname' => $value->address_nickname,
                    'delivery_instruction' => $value->delivery_instruction,
                    'is_primary' => $value->is_primary
                ];
            }

            $address = collect($address);
        }

        return $address;
    }

    public function getCountCorrespondenceAddress($customer_id)
    {
        $count_correspondence_address = DB::table('customer_addresses')
                ->where('customer_addresses.customer_id', '=', $customer_id)
                ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                ->where('addresses.type', 'correspondence')
                ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                ->count();

        return $count_correspondence_address;
    }

    public function createCorrespondenceAddress($customer, $buyer_info)
    {
        $count_correspondence_address = $this->getCountCorrespondenceAddress($customer->id);

        if($count_correspondence_address <= 0){
            $country = DB::table('countries')->where('iso_3166_2',$buyer_info['country_code'])->first();
            $country_of_residence = isset($country)?$country->id:702;

            $address1 = $buyer_info['address1'];
            if( isset($buyer_info['address2']) ){
                $address1 = $address1. ',' .$buyer_info['address2'];
            }
            if( isset($buyer_info['address3']) ){
                $address1 = $address1. ',' .$buyer_info['address3'];
            }

            ### Create Correpondence Address
            $cust_address_data = [
                'country_id'=>$country_of_residence,
                'address1'=>$address1,
                'city'=>$buyer_info['town_city'],
                'state'=>$buyer_info['county_state'] ?? null,
                'postal_code'=>$buyer_info['post_code_zip'],
            ];
            // \Log::channel('checkAuctionLog')->info('CustomerId-'.$customer->id.' cust_address_data '.print_r($cust_address_data,true));
            $this->update($customer->id, $cust_address_data);

            $address_payload = [
                'type'=>'correspondence',
                'firstname'=>$buyer_info['first_name'],
                'lastname'=>$buyer_info['last_name'],
                'address'=>$address1,
                'city'=>$buyer_info['town_city'],
                'state'=>$buyer_info['county_state'] ?? null,
                'country_id'=>$country_of_residence,
                'postalcode'=>$buyer_info['post_code_zip'],
            ];

            if(count($address_payload) > 0){
                // \Log::channel('checkAuctionLog')->info('CustomerId-'.$customer->id.' address_payload data '.print_r($address_payload,true));
                $address_id = DB::table('addresses')->insertGetId($address_payload + NHelpers::created_updated_at());

                $customer_address = [
                    'customer_id' => $customer->id,
                    'address_id' => $address_id,
                    'is_primary' => 0,
                ];
                // \Log::channel('checkAuctionLog')->info('CustomerId-'.$customer->id.' customer_address data '.print_r($customer_address,true));
                DB::table('customer_addresses')->insert($customer_address + NHelpers::created_updated_at());
            }
        }
    }

    public function getCutomerNote($customer_id)
    {
        // dd($customer_id);
        $notes = [];
        $customer_notes = CustomerNote::where('customer_id',$customer_id)->get();

        foreach ($customer_notes as $key => $value) {
            $admin = User::find($value->user_id);
            $notes[] = [
                'note_id' => $value->id,
                'user_id' => $value->user_id,
                'admin_name' => $admin->name,
                'note' => $value->note,
                'date' => $value->created_at,
            ];
        }
        return $notes;
    }

    public function getCountKycAddress($customer_id)
    {
        $count_kyc_address = DB::table('customer_addresses')
                ->where('customer_addresses.customer_id', '=', $customer_id)
                ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                ->where('addresses.type', 'kyc')
                ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                ->count();

        return $count_kyc_address;
    }

    public function getKycAddress($customer_id)
    {
        $kyc_address = DB::table('customer_addresses')
                ->where('customer_addresses.customer_id', '=', $customer_id)
                ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                ->where('addresses.type', 'kyc')
                ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                ->first();

        return $kyc_address;
    }
}
