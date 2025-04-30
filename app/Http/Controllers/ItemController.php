<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest; // Form Request khusus untuk validasi produk
use App\Models\Item; // Model untuk tabel produk
use App\Models\Kategori; // Model untuk tabel kategori
use App\Models\Satuan; // Model untuk tabel unit
use App\Traits\ImageHandlerTrait; // Trait untuk menangani operasi gambar
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    use ImageHandlerTrait;

    /**
     * Tampilkan daftar produk.
     */
    public function index()
    {
        $items = Item::with(['kategori', 'satuan']) // Menambahkan eager loading untuk satuan
        ->when(request('nama'), function ($query) {
            $query->where('nama', 'like', '%' . request('nama') . '%');
        })
        ->when(request('kategori_id'), function ($query) {
            $query->where('kategori_id', request('kategori_id'));
        })
        ->select(['id', 'nama','gambar', 'harga_jual', 'kategori_id', 'satuan_id', 'created_at']) // Optimasi query
        ->latest()
        ->paginate(10)
        ->withQueryString(); // Mempertahankan parameter filter

        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']); // Data untuk dropdown filter

        return view('item.index', compact('items', 'kategoris'));


    }

    /**
     * Tampilkan form untuk membuat produk baru.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        // Ambil semua kategori dan unit dari database.
        $kategoris = Kategori::all();
        $satuans = Satuan::all();

        return view('item.create',['kategoris' => $kategoris,'satuans' => $satuans]);
    }

    /**
     * Simpan produk baru ke dalam database.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ItemRequest $request)
    {
        try {
            // Inisialisasi nama gambar sebagai null (default tidak ada gambar).
            $imageName = null;

            // Jika ada file gambar yang diunggah, simpan gambar di folder 'products' dan dapatkan nama file.
            if ($request->hasFile('gambar')) {
                $imageName = $this->uploadImage($request->file('gambar'), 'Items');
            }

            // Buat produk baru dengan data yang telah divalidasi, sertakan nama gambar jika ada.
            Item::create(array_merge(
                $request->validated(),
                ['gambar' => $imageName]
            ));


            return redirect()->route('item.index')
                   ->with('success', 'Item berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }

    }

    /**
     * Tampilkan form untuk mengedit produk tertentu.
     *
     * @param  \App\Models\Product  $product
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        $items = Item::findOrFail($id);
        // Ambil semua kategori dan unit dari database.
        $kategoris = Kategori::all();
        $satuans = Satuan::all();

        return view('item.edit',['item' => $items ,'kategoris' => $kategoris,'satuans' => $satuans]);
    }

    /**
     * Perbarui data produk dalam database.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ItemRequest $request, Item $item)
    {
        try {
            // Jika ada file gambar baru yang diunggah, perbarui gambar dan hapus gambar lama.
            if ($request->hasFile('gambar')) {
                $item->gambar = $this->updateImage($item->gambar, $request->file('gambar'), 'items');
            }

            // Perbarui data produk dengan data yang diterima dari permintaan.
            $item->nama = $request->nama;
            $item->kategori_id = $request->kategori_id;
            $item->satuan_id = $request->satuan_id;
            $item->harga_jual = $request->harga_jual;
            $item->save();

            // Arahkan pengguna kembali ke halaman daftar produk dengan pesan sukses.
            return redirect()->route('item.index')->with('success', 'Item berhasil diedit');
        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Hapus produk dari database.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item)
    {
        // Jika produk memiliki gambar, hapus gambar dari folder 'items' di disk 'public'.
        if ($item->gambar) {
            Storage::disk('public')->delete('items/' . $item->gambar);
        }

        // Hapus data produk dari database.
        $item->delete();

        // Arahkan pengguna kembali ke halaman sebelumnya dengan pesan sukses.
        return redirect()->route('item.index')->with('success', 'Item berhasil dihapus');
    }
}
