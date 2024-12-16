<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
// use Illuminate\Contracts\Validation\Rule as ValidationRule;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
        $projectId = $this->route('id');

        switch ($this->input('step')) {
            case 1:
                return [
                    'client_id' => [
                        'required',
                        Rule::exists('clients', 'id'),
                    ],
                    'manager_id' => [
                        'required',
                        Rule::exists('users', 'id'),
                    ],
                    'start_date' => 'required|date',
                    'name' => [
                        'required',
                        'max:70',
                        'min:2',
                        // Rule::unique('projects', 'name')->ignore($projectId),
                    ],
                ];
                break;

            case 2:
                return [
                    // 'resource_id' => 'required|array',
                    'resource_id.*' => 'exists:users,id', 
                    // 'department_id' => 'required',
                    // 'estimation_value' => 'required',
                    // 'estimation_type' => 'required',
                ];
                break;

            default:
                return [];
        }
    }

}
