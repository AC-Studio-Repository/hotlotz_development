<?php

namespace App\Modules\SellWithUs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MultipleUnique;
use Illuminate\Support\Arr;
use Hash;

class StoreSellWithUsInfoRequest extends FormRequest
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
            'hide_sellwithus_image_ids'  => 'required',
            'blog_header_1' => 'required',
            'blog_1' => 'required',
            'blog_header_2' => 'required',
            'blog_2' => 'required'
        ];
    }
}
