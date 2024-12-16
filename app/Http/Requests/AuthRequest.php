<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class AuthRequest extends FormRequest
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
            'employee_id'   => 'required',
            'password'      => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
            ],
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
            'password.regex'    =>  'Password must have at least one Uppercase, one Lowercase, one Number, and one Special Character.'
        ];

        return $rules;
    }
}
