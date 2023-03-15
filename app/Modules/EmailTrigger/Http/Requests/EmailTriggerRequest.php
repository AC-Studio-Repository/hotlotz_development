<?php

namespace App\Modules\EmailTrigger\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailTriggerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            // 'parent_id' => 'required',
        ];
        if(isset($this->key)){
            $rules['value'] = 'required';
        }

        return $rules;
    }

}
