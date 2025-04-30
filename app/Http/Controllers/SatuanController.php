<?php

namespace App\Http\Controllers;

use App\Http\Requests\SatuanRequest;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::when(request('nama'), function($query) {
            return $query->where('nama', 'like', '%' . request('nama') . '%');
        })->latest()->paginate(10);

        return view('satuan.index',compact('satuan'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(SatuanRequest $request)
    {
        try {
            Satuan::create($request->validated());

            return redirect()->route('satuan.index')
                   ->with('success', 'Satuan berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $satuan = Satuan::findOrFail($id);

        return view('satuan.edit',compact('satuan'));
    }

    public function update(SatuanRequest $request, Satuan $satuan)
    {
        try{
            $satuan->update($request->validated());

            return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diubah');
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('satuan.index')
                   ->with('error', 'Satuan tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('satuan.index')
                   ->with('error', 'Gagal mengubah satuan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $satuan = Satuan::findOrFail($id);
            $satuan->delete();

            return redirect()->route('satuan.index')
                   ->with('success', 'Satuan berhasil dihapus');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('satuan.index')
                   ->with('error', 'Satuan tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('satuan.index')
                   ->with('error', 'Gagal menghapus satuan: ' . $e->getMessage());
        }
    }
}
