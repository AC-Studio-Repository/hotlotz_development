<?php

namespace App\Modules\HomePage\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MultipleUnique;
use Illuminate\Support\Arr;
use Hash;

class StoreHomePageRequest extends FormRequest
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
            // 'slider_1_caption_title' => 'required|max:255',
            // 'slider_1_caption_content' => 'required|max:255',
            // 'hide_homepage_banner_1'  => 'required',
            // 'slider_2_caption_title' => 'required|max:255',
            // 'slider_2_caption_content' => 'required|max:255',
            // 'hide_homepage_banner_2'  => 'required',
            // 'slider_3_caption_title' => 'required|max:255',
            // 'slider_3_caption_content' => 'required|max:255',
            // 'hide_homepage_banner_3'  => 'required',
            // 'hide_homepage_mareketplace_banner'  => 'required',
            // 'hide_homepage_mareketplace_banner_2'  => 'required',
            // 'hide_homepage_mareketplace_banner_3'  => 'required',
            'hide_homepage_banner_1'  => 'required_with:slider_1_caption_title',
            'hide_homepage_banner_2'  => 'required_with:slider_2_caption_title',
            'hide_homepage_banner_3'  => 'required_with:slider_3_caption_title'
        ];
    }
}
