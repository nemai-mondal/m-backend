<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class HRRequest extends FormRequest
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
            'title'         =>  'required',
            'description'   =>  'required',
            // 'user_id'   =>  [
            //     'required',
            //     Rule::exists('users', 'id')->where(function ($query) {
            //         $query->whereNull('deleted_at')->where('status', 1);
            //     }),
            // ],
            'designation_id' => [
                'nullable',
                Rule::exists('designations', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('status', 1);
                })
            ],
            'department_id' => [
                'nullable',
                Rule::exists('departments', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('status', 1);
                })
            ],
            'event_date'       => 'required|date',
            'event_start_time' => 'required|date_format:H:i:s',
            'event_end_time'   => 'required|date_format:H:i:s|after:event_start_time',
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
            'quote'             =>  'The Quote has already been saved.',
        ];

        return $rules;
    }
}
