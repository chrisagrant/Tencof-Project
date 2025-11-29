<?php

namespace App\Http\Requests\Satuan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSatuanRequest extends FormRequest
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
            'name' => 'required|string|max:50|unique:satuans,name,' . $this->satuan->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama satuan sudah ada.',
        ];
    }
}
