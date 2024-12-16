<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class TimeLogRequest extends FormRequest
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
            // 'date'          => 'required',
            // 'time'          => 'required',
            // 'user_id'       => 'required|integer',
            'activity'      => 'required',
            'terminal'      => 'required',
        ];

        $customMessages = [
            'required'      =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
