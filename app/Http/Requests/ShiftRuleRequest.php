<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ShiftRuleRequest extends FormRequest
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
            'name'         =>  'required',
            'time'         =>  'required',
            
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
