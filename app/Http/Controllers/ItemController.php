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

        // Arahkan pengguna kembali ke halaman daftar produk dengan pesan sukses.
        return redirect()->route('item.index');
    }

    // /**
    //  * Tampilkan form untuk mengedit produk tertentu.
    //  *
    //  * @param  \App\Models\Product  $product
    //  * @return \Inertia\Response
    //  */
    // public function edit($id)
    // {
    //     $product = Product::findOrFail($id);
    //     // Ambil semua kategori dan unit dari database.
    //     $categories = Category::all();
    //     $units = Unit::all();

    //     // Kirim data produk, kategori, dan unit ke komponen Inertia 'Admin/Products/Edit'.
    //     return inertia('Admin/Products/Edit', [
    //         'product' => $product,
    //         'categories' => $categories,
    //         'units' => $units,
    //     ]);
    // }

    // /**
    //  * Perbarui data produk dalam database.
    //  *
    //  * @param  \App\Http\Requests\ProductRequest  $request
    //  * @param  \App\Models\Product  $product
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    // public function update(ProductRequest $request, Product $product)
    // {
    //     // Jika ada file gambar baru yang diunggah, perbarui gambar dan hapus gambar lama.
    //     if ($request->hasFile('image')) {
    //         $product->image = $this->updateImage($product->image, $request->file('image'), 'products');
    //     }

    //     // Perbarui data produk dengan data yang diterima dari permintaan.
    //     $product->name = $request->name;
    //     $product->barcode = $request->barcode;
    //     $product->category_id = $request->category_id;
    //     $product->unit_id = $request->unit_id;
    //     $product->selling_price = $request->selling_price;
    //     $product->save();

    //     // Arahkan pengguna kembali ke halaman daftar produk dengan pesan sukses.
    //     return redirect()->route('admin.products.index');
    // }

    // /**
    //  * Hapus produk dari database.
    //  *
    //  * @param  \App\Models\Product  $product
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    // public function destroy(Product $product)
    // {
    //     // Jika produk memiliki gambar, hapus gambar dari folder 'products' di disk 'public'.
    //     if ($product->image) {
    //         Storage::disk('public')->delete('products/' . $product->image);
    //     }

    //     // Hapus data produk dari database.
    //     $product->delete();

    //     // Arahkan pengguna kembali ke halaman sebelumnya dengan pesan sukses.
    //     return back();
    // }
}
