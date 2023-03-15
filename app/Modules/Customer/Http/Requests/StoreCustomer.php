<?php

namespace App\Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Konekt\Customer\Models\CustomerTypeProxy;
use App\Rules\MultipleUnique;
use Illuminate\Support\Arr;
use Hash;
use App\Modules\Customer\Models\Customer;

class StoreCustomer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'type'  => ['required', Rule::in(CustomerTypeProxy::values())],
            'salutation' => 'required|string',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:100', new MultipleUnique('customers', 'email', false, [])],
            // 'password' => 'required|string|min:8|max:100|confirmed',
            // 'password_confirmation' => 'required|string|min:8|max:100',
        ];
    }

    public function payload($request)
    {
        $payload = Arr::except($request, ['customer_id','hide_customer_ids','customer_document','xero_item_id','price','notes']);
        
        $payload = Arr::add($payload, 'password', Hash::make('password'));
        $payload = Arr::add($payload, 'hash_password', Hash::make('password'));
        
        $payload = Arr::add($payload, 'is_active', 1);
        $payload = Arr::add($payload, 'fullname', $payload['firstname'].' '.$payload['lastname']);
        $payload = Arr::add($payload, 'ref_no', Customer::getCustomerRefNo());

        $payload['category_interests'] = null;
        $payload['salutation'] = $request['salutation'];
        $payload['title'] = $request['salutation'];
        
        $payload['marketing_auction'] = isset($request['marketing_auction'])?1:0;
        $payload['marketing_marketplace'] = isset($request['marketing_marketplace'])?1:0;
        $payload['marketing_chk_events'] = isset($request['marketing_chk_events'])?1:0;
        $payload['marketing_chk_congsignment_valuation'] = isset($request['marketing_chk_congsignment_valuation'])?1:0;
        $payload['marketing_hotlotz_quarterly'] = isset($request['marketing_hotlotz_quarterly'])?1:0;
        
        $payload['sr_customer_data'] = [];

        if (isset($request['country_of_residence']) && $request['country_of_residence'] == '702') {
            $payload['buyer_gst_registered'] = 1;
        } else {
            $payload['buyer_gst_registered'] = 0;
        }

        if(isset($request['exclude_marketing_material']) && $request['exclude_marketing_material'] == 1){
            $payload['marketing_auction'] = 0;
            $payload['marketing_marketplace'] = 0;
            $payload['marketing_chk_events'] = 0;
            $payload['marketing_chk_congsignment_valuation'] = 0;
            $payload['marketing_hotlotz_quarterly'] = 0;
        }

        if (isset($request['type']) && $request['type'] == 'organization') {
            $payload['reg_behalf_company'] = 1;
        }else{
            $payload['reg_behalf_company'] = 0;
        }

        if (isset($request['seller_gst_registered']) && $request['seller_gst_registered'] == 1) {
            $payload['seller_gst_registered'] = 1;
            $payload['reg_gst_sg'] = 1;
        }else{
            $payload['seller_gst_registered'] = 0;
            $payload['reg_gst_sg'] = 0;
        }

        return $payload;
    }
}
