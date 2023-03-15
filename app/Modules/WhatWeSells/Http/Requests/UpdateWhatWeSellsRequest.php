<?php

namespace App\Modules\WhatWeSells\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWhatWeSellsRequest extends FormRequest
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
        $rules =  [
            'caption' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required',
        ];

        return $rules;
    }

}
