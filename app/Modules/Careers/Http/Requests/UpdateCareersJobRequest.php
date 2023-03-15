<?php

namespace App\Modules\Careers\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MultipleUnique;
use Illuminate\Support\Arr;
use App\Modules\Careers\Models\Careers;
use Hash;

class UpdateCareersJobRequest extends FormRequest
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
        return [
            // 'title'  => 'required',
            // 'description'  => 'required',
            // 'hide_sp_image_ids'  => 'required',
        ];
    }
}
