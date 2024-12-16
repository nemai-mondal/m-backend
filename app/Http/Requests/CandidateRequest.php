<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class CandidateRequest extends FormRequest
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
            'designation_id'           =>  [
                'required',
                Rule::exists('designations', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('status', 1);
                }),
            ],
            'department_id'            =>  [
                'required',
                Rule::exists('departments', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('status', 1);
                }),
            ],

            
            'name' => 'required|min:3|max:70',
            'date_of_joining' => 'required|date',
            
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
