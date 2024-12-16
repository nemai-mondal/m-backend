<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name'  =>  [
                'required',
                Rule::unique('roles', 'name')
            ],
            'permissions.*' => [
                'required',
                Rule::exists('permissions', 'id')
            ]
        ];

        return $rules;
    }
}
