<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ActivityRequest extends FormRequest
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
        $activityId = $this->route('id');

        $rules = [
            'name' => [
                'required',
                'max:70',
                Rule::unique('activities', 'name')->ignore($activityId),
            ],
        ];

        return $rules;
    }
}
