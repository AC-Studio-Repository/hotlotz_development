<?php

namespace App\Modules\Customer\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Konekt\Customer\Models\CustomerTypeProxy;
use App\Rules\MultipleUnique;
use App\Modules\Customer\Rules\CustomerOldPassword;
use Illuminate\Support\Arr;
use App\Modules\Customer\Models\Customer;
use Hash;

class UpdateCustomer extends FormRequest
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
    public function rules(Request $request)
    {
        $rules = [];
        if($request->tab_name == 'contact_details') {
            $rules =  [
                // 'salutation' => 'required|string',
                // 'firstname' => 'required|string|max:100',
                // 'lastname' => 'required|string|max:100',
                // 'email' => ['required', 'string', 'email', 'max:100', new MultipleUnique('customers', 'email', $this->route('customer')->id, [])],
                'email' => "required|string|email|max:255|unique:customers,email,".$this->route('customer')->id.",id,deleted_at,NULL",
            ];

            // if(isset($request->old_password)) {
            //     $customer = Customer::findOrFail($this->route('customer')->id);
            //     $rules['old_password'] = ['required', 'string', 'min:8', 'max:100', new CustomerOldPassword($customer)];
            //     $rules['new_password'] = 'required|string|min:8|max:100|different:old_password';
            // }
        }

        return $rules;
    }

    public function payload($request)
    {
        $data['tab_name'] = $request['tab_name'];
        $payload = [];
        $data['payload'] = $payload;
        if($request['tab_name'] == 'contact_details') {
            // dd(DB::getQueryLog())
            // if(isset($request['old_password'])){
            //     $payload = Arr::add($request, 'password',  Hash::make($request['new_password']));
            //     $payload = Arr::add($payload, 'hash_password',  Hash::make($request['new_password']));
            //     $payload = Arr::except($payload, ['password_confirmation','old_password','new_password']);
            // }else{
            //     $payload = Arr::except($request, ['password_confirmation','password','old_password','new_password']);
            // }
            $payload = Arr::except($request, ['customer_id','hide_customer_ids','customer_document','xero_item_id','price','notes']);

            if( isset($payload['firstname']) && isset($payload['lastname']) ){
                $payload = Arr::add($payload, 'fullname', $payload['firstname'].' '.$payload['lastname']);
            }

            if( isset($request['salutation']) ){
                $payload['salutation'] = $request['salutation'];
                $payload['title'] = $request['salutation'];
            }
            $payload['category_interests'] = null;
            $payload['sr_customer_data'] = [];

            if (isset($request['country_of_residence']) && $request['country_of_residence'] == '702') {
                $payload['buyer_gst_registered'] = 1;
            } else {
                $payload['buyer_gst_registered'] = 0;
            }

            if (isset($request['type']) && $request['type'] == 'organization') {
                $payload['reg_behalf_company'] = 1;
            }else{
                $payload['reg_behalf_company'] = 0;
            }
        }

        if($request['tab_name'] == 'seller_details') {
            $payload['note_to_appear_on_statement'] = $request['note_to_appear_on_statement'];

            if (isset($request['seller_gst_registered']) && $request['seller_gst_registered'] == 1) {
                $payload['seller_gst_registered'] = 1;
                $payload['reg_gst_sg'] = 1;
                $payload['gst_number'] = $request['gst_number'];
                $payload['sg_uen_number'] = $request['sg_uen_number'];
            }else{
                $payload['seller_gst_registered'] = 0;
                $payload['reg_gst_sg'] = 0;
                $payload['gst_number'] = 0;
                $payload['sg_uen_number'] = 0;
            }
        }

        if($request['tab_name'] == 'buyer_details') {
            $payload['note_to_appear_on_invoice'] = $request['note_to_appear_on_invoice'];
        }

        if($request['tab_name'] == 'marketing') {
            $payload['marketing_auction'] = isset($request['marketing_auction'])?1:0;
            $payload['marketing_marketplace'] = isset($request['marketing_marketplace'])?1:0;
            $payload['marketing_chk_events'] = isset($request['marketing_chk_events'])?1:0;
            $payload['marketing_chk_congsignment_valuation'] = isset($request['marketing_chk_congsignment_valuation'])?1:0;
            $payload['marketing_hotlotz_quarterly'] = isset($request['marketing_hotlotz_quarterly'])?1:0;

            if(isset($request['exclude_marketing_material']) && $request['exclude_marketing_material'] == 1){
                $payload['marketing_auction'] = 0;
                $payload['marketing_marketplace'] = 0;
                $payload['marketing_chk_events'] = 0;
                $payload['marketing_chk_congsignment_valuation'] = 0;
                $payload['marketing_hotlotz_quarterly'] = 0;
            }
            $payload['exclude_marketing_material'] = (int) $request['exclude_marketing_material'];
        }

        if($request['tab_name'] == 'documents') {
            //
        }

        if($request['tab_name'] == 'adhoc_invoice') {
            //
        }

        if($request['tab_name'] == 'payments') {
            $payload = Arr::except($request, ['customer_id','tab_name']);
        }

        if($request['tab_name'] == 'kyc') {
            $payload = Arr::except($request, ['customer_id','tab_name','birth_date','passport_ep_date','hide_nric_doc_ids','hide_fin_doc_ids','hide_passport_doc_ids']);

            $payload['nric_document_ids'] = $request['hide_nric_doc_ids'] ?? null;
            $payload['fin_document_ids'] = $request['hide_fin_doc_ids'] ?? null;
            $payload['passport_document_ids'] = $request['hide_passport_doc_ids'] ?? null;
        }
        $data['payload'] = $payload;

        return $data;
    }

}