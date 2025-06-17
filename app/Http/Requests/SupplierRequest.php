<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'nama' => 'required|string|max:255|unique:suppliers,nama',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|min:10|max:13|regex:/^[0-9]+$/',
            'desc' => 'nullable|string|max:1000',
            'status' => 'required|in:aktif,tidak aktif',
        ];

        // Jika metode update, abaikan unique untuk supplier saat ini
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['nama'] = 'required|string|max:255|unique:suppliers,nama,'.$this->supplier->id;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama supplier wajib diisi',
            'nama.string' => 'Nama supplier harus berupa teks',
            'nama.max' => 'Nama supplier tidak boleh lebih dari 255 karakter',
            'nama.unique' => 'Nama supplier sudah digunakan, harap gunakan nama lain',

            'alamat.required' => 'Alamat supplier wajib diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter',

            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.string' => 'Nomor HP harus berupa teks',
            'no_hp.min' => 'Nomor HP tidak boleh kurang dari 10 karakter',
            'no_hp.max' => 'Nomor HP tidak boleh lebih dari 20 karakter',
            'no_hp.regex' => 'Nomor HP hanya boleh mengandung angka',

            'desc.string' => 'Deskripsi harus berupa teks',
            'desc.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter',

            'status.required' => 'Status supplier wajib dipilih',
            'status.in' => 'Status harus berupa "aktif" atau "tidak aktif"',
        ];
    }

    /**
     * Nama atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'nama' => 'Nama Supplier',
            'alamat' => 'Alamat Supplier',
            'no_hp' => 'Nomor HP',
            'desc' => 'Deskripsi Supplier',
            'status' => 'Status Supplier',
        ];
    }
}
