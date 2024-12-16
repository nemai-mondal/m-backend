<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class LeaveRatioRequest extends FormRequest
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
            'employment_type_id' => [
                'required',
                'integer',
                Rule::exists('employment_types', 'id'),
            ],
            'leave_credit' => [
                'required',
                'numeric',
                // 'regex:/^\d*\.\d+$/',
            ],
            'leave_type_id' => [
                'required',
                'integer',
                Rule::exists('leave_types', 'id'),
            ],
            'frequency'     =>  [
                'required',
                Rule::in(['monthly', 'yearly']),
            ]
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
