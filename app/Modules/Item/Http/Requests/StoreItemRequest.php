<?php

namespace App\Modules\Item\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItemRequest extends FormRequest
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
            // 'item_number'=>"required|string|max:255|unique:items,item_number,NULL,id,deleted_at,NULL",
            'customer_id' => 'required',
            'long_description' => 'required',
            'category_id' => 'required',
        ];

        return $rules;
    }

}
