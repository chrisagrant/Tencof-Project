<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
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
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'quantity'      => 'required|numeric|min:1',
            'unit_price'    => 'required|numeric|min:0',
        ];
    }
}
