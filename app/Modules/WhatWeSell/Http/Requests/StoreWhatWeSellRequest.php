<?php

namespace App\Modules\WhatWeSell\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MultipleUnique;
use Illuminate\Support\Arr;
use Hash;

class StoreWhatWeSellRequest extends FormRequest
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
            'title'  => 'required',
            'hide_whatwesell_ids'  => 'required',
            'hide_whatwesell_banner_ids'  => 'required',
            // 'price' => 'required',
            // 'price_status' => 'required',
            // 'buyerlevel' => 'required',
            'caption' => 'required',
            'description' => 'required'
        ];
    }
}
