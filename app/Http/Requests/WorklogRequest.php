<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class WorklogRequest extends FormRequest
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
            'activity_id'     =>  [
                'required',
                Rule::exists('activities', 'id'),
            ],
            'client_id'      =>  [
                'required',
                Rule::exists('clients', 'id'),
            ],
            'project_id'      =>  [
                'required',
                Rule::exists('projects', 'id'),
            ],
            'date'          =>  'required|date|before_or_equal:today',
            'time_spent'    =>  'required',
            'description'   =>  'nullable',
        ];

        $customMessages = [
            'required'      =>  'The :attribute field is required.',
        ];
        
        return $rules;

    }
}
