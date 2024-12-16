<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class DesignationRequest extends FormRequest
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
        $designationId = $this->route('id');

        $rules = [
            'name' => [
                'required',
                'max:70',
                Rule::unique('designations', 'name')->ignore($designationId),
            ],
            'department_id' =>  [                
                'required',
                Rule::exists('departments', 'id'),
            ]
        ];

        $customMessages = [
            'required'      =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
