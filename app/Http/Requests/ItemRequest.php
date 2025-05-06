<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
        $itemId = $this->route('item')?->id;

        $rules = [
            'nama' => [
                'required',
                'string',
                'max:255',
                'unique:items,nama,'.$itemId
            ],
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id' => 'required|exists:satuans,id',
            'gambar' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama item wajib diisi',
            'nama.string' => 'Nama item harus berupa teks',
            'nama.max' => 'Nama item tidak boleh lebih dari 255 karakter',
            'nama.unique' => 'Nama item sudah digunakan, harap gunakan nama lain',

            'harga_jual.required' => 'Harga jual wajib diisi',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0',

            'harga_beli.required' => 'Harga beli wajib diisi',
            'harga_beli.numeric' => 'Harga beli harus berupa angka',
            'harga_beli.min' => 'Harga beli tidak boleh kurang dari 0',

            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid',

            'satuan_id.required' => 'Satuan wajib dipilih',
            'satuan_id.exists' => 'Satuan yang dipilih tidak valid',

            'gambar.required' => 'Gambar item wajib diupload',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'gambar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ];
    }

    /**
     * Nama atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'nama' => 'Nama Item',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'kategori_id' => 'Kategori',
            'satuan_id' => 'Satuan',
            'gambar' => 'Gambar Item',
        ];
    }
}
