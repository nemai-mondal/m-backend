<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class LeaveTypeRequest extends FormRequest
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
        $leaveTypeId = $this->route('id');

        $rules = [
            'name' => [
                'required',
                'max:70',
                Rule::unique('leave_types', 'name')->ignore($leaveTypeId),
            ],
            'abbreviation'  =>  [
                'required',
                'max:5',
                    Rule::unique('leave_types', 'abbreviation')->ignore($leaveTypeId),
            ],
            'comment'       =>  'required',
        ];
    
        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];
    
        return $rules;
    }
}
