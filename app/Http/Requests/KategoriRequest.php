<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true; // Mengizinkan semua pengguna untuk membuat permintaan
    }

    /**
     * Mendapatkan aturan validasi yang berlaku untuk permintaan.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'nama' => [
                'required',
                'string',
                'max:255',
                'unique:kategoris,nama'
            ],
            'desc' => 'nullable|string|max:1000',
        ];

        // Jika metode update, abaikan unique untuk kategori saat ini
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['nama'] = [
                'required',
                'string',
                'max:255',
                'unique:kategoris,nama,'.$this->kategori->id
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori wajib diisi',
            'nama.string' => 'Nama kategori harus berupa teks',
            'nama.max' => 'Nama kategori tidak boleh lebih dari 255 karakter',
            'nama.unique' => 'Nama kategori sudah digunakan, harap gunakan nama lain',
            'desc.string' => 'Deskripsi harus berupa teks',
            'desc.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter',
        ];
    }

    /**
     * Nama atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'nama' => 'Nama Kategori',
            'desc' => 'Deskripsi Kategori',
        ];
    }
}
