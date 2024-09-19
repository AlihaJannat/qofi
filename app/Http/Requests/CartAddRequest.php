<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartAddRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:sw_products,id',
            'date' => 'nullable|date|after:today',
            'time' => 'nullable|required_with:date|date_format:H:i:s',
            'quantity' => 'nullable|integer|min:1',
            'personal_message' => 'nullable|max:500',
        ];
    }
}
