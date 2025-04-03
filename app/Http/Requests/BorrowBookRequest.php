<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'barcode' => 'required|string',
            'due_date' => 'required|date|after_or_equal:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'barcode.required' => 'Book selection is required',
            'barcode.string' => 'Invalid book selection format',
            'due_date.required' => 'Return date is required',
            'due_date.date' => 'Invalid return date format',
            'due_date.after_or_equal' => 'Return date must be today or later',
        ];
    }
}
