<?php

namespace App\Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Konekt\Customer\Models\CustomerTypeProxy;
use App\Rules\MultipleUnique;
use Illuminate\Support\Arr;
use Hash;
use App\Modules\Customer\Models\Customer;

class AjaxCreateCustomer extends FormRequest
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
            'salutation' => 'required|string',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email'=>"required|string|email|max:255|unique:customers,email,NULL,id,deleted_at,NULL",
        ];
    }

    public function payload($request)
    {
        $payload = $request;
        $payload['password'] = Hash::make('password');
        $payload['hash_password'] = Hash::make('password');
        $payload['is_active'] = 1;
        $payload['fullname'] = $request['firstname'].' '.$request['lastname'];
        $payload['ref_no'] = Customer::getCustomerRefNo();
        $payload['title'] = $request['salutation'];
        $payload['type'] = 'individual';
        $payload['has_agreement'] = 0;
        // dd($payload);
        
        return $payload;
    }
}
