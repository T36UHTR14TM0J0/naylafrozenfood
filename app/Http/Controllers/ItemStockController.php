<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\StokItem;
use App\Http\Requests\ItemStockRequest;
use App\Models\Kategori;
use App\Models\StokTotal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemStockController extends Controller
{
    public function index()
    {
        // Ambil data StockProduct beserta relasi item dan supplier.
        $productStocks = StokItem::with(['item', 'supplier'])
            ->when(request('nama'), function ($query) {
                $query->whereHas('supplier', function ($query) {
                    $query->where('nama', 'like', '%' . request('nama') . '%');
                });
            })
            ->latest()
            ->paginate(10);

        // Ambil semua supplier untuk dropdown jika diperlukan
        $suppliers = Supplier::all();

        // Sertakan parameter pencarian 'nama' di link paginasi
        $productStocks->appends(['nama' => request('nama')]);

        // Ambil semua item dan kategori untuk tampilan
        $items = Item::with('kategori')->get();
        $categories = Kategori::all();

        return view('item_stok.index', compact('productStocks', 'suppliers', 'items', 'categories'));
    }

    public function create(Request $request)
    {
        // Mengambil ID item dari query string
        $id_item = $request->query('items');
        $item = Item::find($id_item); // Mengambil item berdasarkan ID

        // Jika item tidak ditemukan, redirect atau tampilkan pesan error
        if (!$item) {
            return redirect()->route('stok.index')->with('error', 'Item tidak ditemukan.');
        }

        $suppliers = Supplier::all(); // Ambil semua supplier

        return view('item_stok.create', compact('item', 'suppliers'));
    }

    public function store(ItemStockRequest $request)
    {
        dd($request);
        die;
        
    }
    public function selectItems(ItemStockRequest $request)
    {
        dd($request);
        die;
        
    }
}