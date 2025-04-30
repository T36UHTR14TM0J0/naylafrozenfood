<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use Illuminate\Support\Facades\Http;

class SupplierController extends Controller
{
    /**
     * Tampilkan daftar semua supplier.
     */
    public function index()
    {
        // Ambil data supplier dengan fitur pencarian berdasarkan nama jika ada query 'q'
        $suppliers = Supplier::when(request()->nama, function($suppliers) {
            return $suppliers->where('nama', 'like', '%'. request()->nama . '%');
        })->latest()->paginate(5); // Urutkan berdasarkan yang terbaru dan paginate 5 per halaman

        // Sertakan query string 'q' dalam link paginasi
        $suppliers->appends(['nama' => request()->nama]);

        return view('supplier.index',compact('suppliers'));


    }

    /**
     * Tampilkan form untuk membuat supplier baru.
     */
    public function create()
    {

        return view('supplier.create');
    }

    /**
     * Simpan supplier baru ke dalam database.
     */
    public function store(SupplierRequest $request)
    {
        try {
            // Buat supplier baru berdasarkan data yang telah divalidasi
            Supplier::create($request->validated());

            return redirect()->route('supplier.index')
                   ->with('success', 'Supplier berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }

    }

    /**
     * Tampilkan form untuk mengedit supplier tertentu.
     */
    public function edit($id)
    {
        // Cari supplier berdasarkan ID, error jika tidak ditemukan
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit',compact('supplier'));
    }

    /**
     * Perbarui data supplier yang sudah ada di database.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            // Perbarui data supplier dengan data yang telah divalidasi
            $supplier->update($request->validated());


            return redirect()->route('supplier.index')
                   ->with('success', 'Supplier berhasil diedit');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }

    }

    /**
     * Hapus supplier dari database.
     */
    public function destroy($id)
    {
        // Cari supplier berdasarkan ID, error jika tidak ditemukan
        $supplier = Supplier::findOrFail($id);

        // Hapus supplier dari database
        $supplier->delete();

        // Arahkan kembali ke daftar supplier dengan pesan sukses
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus');
    }
}
