<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
        $clientId = $this->route('id');

        $rules = [
            'name' => [
                'required',
                'max:70',
                Rule::unique('clients', 'name')->ignore($clientId),
            ],
            'type'      =>'required|in:new,existing',
            'site'      =>'required|in:domestic,international',
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
        ];

        return $rules;
    }
}
