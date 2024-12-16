<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class MotivationalQuoteRequest extends FormRequest
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
        return [
            'quote' => [
                'required',
                Rule::unique('motivational_quotes', 'quote')
                    ->ignore($this->route('id'))
            ],
        ];
    }
}
