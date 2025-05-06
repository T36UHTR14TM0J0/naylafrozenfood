<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStockRequest extends FormRequest
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
            'item_id' => [
                'required',
                'exists:items,id'
            ],
            'supplier_id' => [
                'required',
                'exists:suppliers,id'
            ],
            'jumlah_stok' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }
    
    public function messages(): array
    {
        return [
            'item_id.required' => 'Item ID wajib diisi',
            'item_id.exists' => 'Item ID tidak valid, harap pilih item yang ada',
            'supplier_id.required' => 'Supplier ID wajib diisi',
            'supplier_id.exists' => 'Supplier ID tidak valid, harap pilih supplier yang ada',
            'jumlah_stok.required' => 'Jumlah stok wajib diisi',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa angka',
            'jumlah_stok.min' => 'Jumlah stok minimal 1'
        ];
    }
    
    /**
     * Nama atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'item_id' => 'Item',
            'supplier_id' => 'Supplier',
            'jumlah_stok' => 'Jumlah Stok'
        ];
    }
}
