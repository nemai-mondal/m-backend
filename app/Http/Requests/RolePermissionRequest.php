<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class RolePermissionRequest extends FormRequest
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
            'permission_id' =>  [
                'required',
                Rule::exists('permissions', 'id')
            ],
            'role_id' =>  [
                'required',
                Rule::exists('roles', 'id')
            ]
        ];

        return $rules;
    }
}
