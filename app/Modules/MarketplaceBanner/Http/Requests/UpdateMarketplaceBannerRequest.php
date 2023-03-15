<?php

namespace App\Modules\MarketplaceBanner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMarketplaceBannerRequest extends FormRequest
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
            // 'main_title' => 'required|string|max:255',
            // 'sub_title' => 'required|string|max:255',
        ];

        return $rules;
    }

}
