<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class InterviewRequest extends FormRequest
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

        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $rules = [
            'name'                      =>  'required',
            'email'                     =>  [
                'required',
                Rule::unique('interviews', 'email')
            ],
            // 'interview_at'              =>  'required',
            // 'interview_link'            =>  [
            //     'required',
            //     'regex:'.$regex,
            //     Rule::unique('interviews', 'interview_link')
            // ],
            // 'designation_id'   =>  [
            //     'required',
            //     Rule::exists('designations', 'id')
            // ]
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
            'regex'             =>  'Invalid URL.',
        ];

        return $rules;
    }
}
