<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class ChangeStatusRequest extends FormRequest
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
            // 'id'   => [
            //     'required',
            //     Rule::exists('users', 'id'),
            // ],
            'status'      => [
                'required',
                Rule::in([0,1]),
                ]
        ];

        $customMessages = [
            'required'      =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
