<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductVariationRequest extends FormRequest
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
            'variation_id' => 'required|integer',
            'product_variation_set_id' => 'required|integer',
            'parent_product_id' => [
                'required',
                Rule::unique('sw_product_variations')
                    ->where(function ($query) {
                        return $query->where('variation_id', $this->variation_id)
                            ->where('product_variation_set_id', $this->product_variation_set_id);
                    })
            ],
        ];
    }

    public function messages()
    {
        return [
            'parent_product_id.unique' => 'Variation Already Exists',
        ];
    }
}
