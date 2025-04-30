<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SatuanRequest extends FormRequest
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
        $rules = [
            'nama' => 'required|string|max:255|unique:satuans,nama',
        ];

        // Jika metode update, abaikan unique untuk supplier saat ini
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['nama'] = 'required|string|max:255|unique:satuans,nama,'.$this->satuan->id;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama satuan wajib diisi',
            'nama.string' => 'Nama satuan harus berupa teks',
            'nama.max' => 'Nama satuan tidak boleh lebih dari 255 karakter',
            'nama.unique' => 'Nama satuan sudah digunakan, harap gunakan nama lain',
        ];
    }

    /**
     * Nama atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'nama' => 'Nama satuan',
        ];
    }
}
