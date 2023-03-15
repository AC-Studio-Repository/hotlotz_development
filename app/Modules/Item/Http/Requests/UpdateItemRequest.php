<?php

namespace App\Modules\Item\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class UpdateItemRequest extends FormRequest
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
        $rules =  [
            // 'item_number' => "required|string|max:255|unique:items,item_number,".$this->route('item').",id,deleted_at,NULL",
        ];

        return $rules;
    }

}
