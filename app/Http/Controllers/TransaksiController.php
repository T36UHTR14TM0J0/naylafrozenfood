<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
{
    // Ambil semua produk untuk ditampilkan di halaman transaksi
    $items = Item::all(); // Anda bisa menambahkan logika untuk filter jika perlu
    
    // Jika Anda ingin menambahkan data lainnya, seperti transaksi yang sudah ada:
    $transaksi = Transaksi::latest()->take(10)->get(); // Menampilkan 10 transaksi terbaru

    // Mengirim data ke view
    return view('transaksi.index', compact('items', 'transaksi'));
}
}
