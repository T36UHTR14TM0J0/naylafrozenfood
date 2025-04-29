<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{

    public function index()
    {
        $kategori = Kategori::when(request('nama'), function($query) {
            return $query->where('nama', 'like', '%' . request('nama') . '%');
        })->latest()->paginate(10);

        return view('kategori.index',compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(KategoriRequest $request)
    {
        try {
            Kategori::create($request->validated());

            return redirect()->route('kategori.index')
                   ->with('success', 'Kategori berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('kategori.edit',compact('kategori'));
    }

    public function update(KategoriRequest $request, Kategori $kategori)
    {
        try{
            $kategori->update($request->validated());

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diubah');
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'Kategori tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'Gagal mengubah kategori: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return redirect()->route('kategori.index')
                   ->with('success', 'Kategori berhasil dihapus');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'Kategori tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
