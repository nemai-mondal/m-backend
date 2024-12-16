<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rule;

class HolidayRequest extends FormRequest
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
        $holidayId = $this->route('id');

        $rules = [
            'holiday_name' => [
                'required',
                'min:2',
                'max:70',
                Rule::unique('holidays', 'holiday_name')
                ->ignore($holidayId)
                ->where(function ($query) {
                    $query->where('date_from', $this->input('date_from'))
                          ->where('date_to', $this->input('date_to'));
                }),
            ],
            'date_from' => 'required|date|date_format:Y-m-d',
            'date_to'   => 'required|date|date_format:Y-m-d|after_or_equal:date_from',
            'days'      => 'required|numeric|min:1',
        ];
    
        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];
    
        return $rules;
    
    }
}
