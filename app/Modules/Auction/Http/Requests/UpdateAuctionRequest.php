<?php

namespace App\Modules\Auction\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAuctionRequest extends FormRequest
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
        $rules = [
            'type' => 'required|string',
            'title' => 'required|string|max:100',
            'confirmation_email' => 'required|string|email|max:100',
            'registration_email' => 'required|string|email|max:100',
            'payment_receive_email' => 'required|string|email|max:100',
            'timed_start' => 'required',
            'timed_first_lot_ends' => 'required',            
        ];

        return $rules;
    }
}
