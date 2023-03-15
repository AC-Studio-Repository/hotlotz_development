<?php

namespace App\Modules\HomePageRandomText\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MultipleUnique;
use Illuminate\Support\Arr;
use App\Modules\HomePageRandomText\Models\HomePageRandomText;
use Hash;

class UpdateHomePageRandomTextRequest extends FormRequest
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
            'title'  => 'required',
            'description'  => 'required',
        ];
    }
}
