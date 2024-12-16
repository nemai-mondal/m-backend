<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class LeaveApplicationRequest extends FormRequest
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
            'application_id' => [
                'required',
                'integer',
                Rule::exists('leave_applications', 'id')->where(function ($query) {
                    $query->where('leave_status', 'pending');
                }),
            ],
            // 'employee_id' => [
            //     'required',
            //     'integer',
            //     Rule::exists('users', 'id'),
            // ],
            'action'     =>  [
                'required',
                Rule::in(['approved', 'rejected', 'cancelled']),
            ]
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
