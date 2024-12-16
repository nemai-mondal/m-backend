<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class MediaRequest extends FormRequest
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
        $amendment = $this->route('id');

        $rules = [
            'added_by_id'  =>  [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('status', 1);
                }),
            ],
            'file' => 'file|required|mimes:pdf,doc,docx,jpg,jpeg|max:5120', 
            'name' => [
                'nullable',
                'max:70',
                Rule::unique('amendments', 'name')->ignore($amendment),
            ],
        ];

        $customMessages = [
            'required'          =>  'The :attribute field is required.',
            'name.unique'       => 'The name has already been taken.',
            'file.mimes'        => 'The :attribute must be a file of type: pdf, doc, docx, jpg, jpeg.',
            'file.max'          => 'The :attribute may not be greater than :max kilobytes.',
        ];

        return $rules;
    }

}
