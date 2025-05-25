<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStockRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true; // Mengizinkan semua pengguna untuk mengakses request ini
    }

    /**
     * Mendapatkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => [
                'required', // Item wajib diisi
                'exists:items,id', // Pastikan item ada di tabel 'items'
            ],
            'supplier_id' => [
                'required', // Supplier wajib diisi
                'exists:suppliers,id', // Pastikan supplier ada di tabel 'suppliers'
            ],
            'jumlah_stok' => [
                'required', // Jumlah stok wajib diisi
                'integer', // Jumlah stok harus berupa angka
                'min:1', // Jumlah stok minimal 1
            ],
            'harga' => 'required|numeric|min:0', // Harga wajib diisi, harus berupa angka dan minimal 0
        ];
    }
    
    /**
     * Mendapatkan pesan kustom untuk validasi error.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'item_id.required' => 'Item wajib diisi', // Pesan error jika item_id kosong
            'item_id.exists' => 'Item tidak valid, harap pilih item yang ada', // Pesan error jika item_id tidak ada di database
            'supplier_id.required' => 'Supplier wajib diisi', // Pesan error jika supplier_id kosong
            'supplier_id.exists' => 'Supplier tidak valid, harap pilih supplier yang ada', // Pesan error jika supplier_id tidak ada di database
            'jumlah_stok.required' => 'Jumlah stok wajib diisi', // Pesan error jika jumlah_stok kosong
            'jumlah_stok.integer' => 'Jumlah stok harus berupa angka', // Pesan error jika jumlah_stok bukan angka
            'jumlah_stok.min' => 'Jumlah stok minimal 1', // Pesan error jika jumlah_stok kurang dari 1
            'harga.required' => 'Harga wajib diisi', // Pesan error jika harga kosong
            'harga.numeric' => 'Harga harus berupa angka', // Pesan error jika harga bukan angka
            'harga.min' => 'Harga tidak boleh kurang dari 0', // Pesan error jika harga kurang dari 0
        ];
    }
    
    /**
     * Mendefinisikan nama atribut kustom untuk pesan error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'item_id' => 'Item', // Nama kustom untuk 'item_id'
            'supplier_id' => 'Supplier', // Nama kustom untuk 'supplier_id'
            'jumlah_stok' => 'Jumlah Stok', // Nama kustom untuk 'jumlah_stok'
            'harga' => 'Harga', // Nama kustom untuk 'harga'
        ];
    }
}
