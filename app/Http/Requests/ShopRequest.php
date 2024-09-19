<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
            'owner_id' => 'required',
            'sw_shop_category_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'latitude' => [
                'required',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
            'longitude' => [
                'required',
                'regex:/^[-]?(([1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/'
            ],
        ];
    }
}
