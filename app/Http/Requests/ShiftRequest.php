<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ShiftRequest extends FormRequest
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
        try {
            $rules = [
                'name' => [
                    'required',
                    'min:2',
                    'max:70',
                    Rule::unique('shifts')
                        ->where('name', request('name'))
                        ->where('shift_start', request('shift_start'))
                        ->where('shift_end', request('shift_end'))
                        ->where('timezone', json_encode(request('timezone')))
                ],
                'shift_start'   => 'required|date_format:H:i:s',
                'shift_end'     => 'required|date_format:H:i:s',
                'timezone'      => 'required',
            ];
    
            return $rules;
        } catch (Exception $e) {
            Log::error('Validation Exception: ' . $e->getMessage());
            throw $e;
        }
    }
    
}
