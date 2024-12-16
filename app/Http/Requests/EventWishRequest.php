<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class EventWishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        $rules = [
            'wish_to_id'   => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'message'      => 'required',
        ];

        $customMessages = [
            'required'      =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
