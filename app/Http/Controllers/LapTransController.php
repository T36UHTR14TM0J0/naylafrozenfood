<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LapTransController extends Controller
{
    /**
     * Menampilkan laporan transaksi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Tentukan default tanggal jika tanggal_awal atau tanggal_akhir tidak ada dalam request
        $tanggal_awal = request('tanggal_awal') ?: now()->format('Y-m-d'); // Jika tidak ada tanggal_awal, set ke hari ini
        $tanggal_akhir = request('tanggal_akhir') ?: now()->addDays(30)->format('Y-m-d'); // Jika tidak ada tanggal_akhir, set ke 30 hari ke depan

        // Ambil data transaksi sesuai dengan rentang tanggal
        $transactions = Transaksi::whereBetween('tanggal_transaksi', [$tanggal_awal, $tanggal_akhir])
            ->when(request('metode_pembayaran'), function ($query) {
                $query->where('metode_pembayaran', request('metode_pembayaran')); // Filter berdasarkan metode_pembayaran (success/pending)
            })
            ->orderBy('tanggal_transaksi', 'desc') // Mengurutkan berdasarkan tanggal transaksi terbaru
            ->paginate(10);

        // Sertakan parameter pencarian 'metode_pembayaran' dan tanggal di link paginasi
        $transactions->appends([
            'metode_pembayaran' => request('metode_pembayaran'),
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
        ]);

        // Menghitung total transaksi
        $totalTransactions = $transactions->total();

        // Kembalikan view dengan data terkait
        return view('laporan.transaksi.index', compact('transactions', 'tanggal_awal', 'tanggal_akhir', 'totalTransactions'));
    }


    public function detail($id)
    {
        // Ambil data transaksi beserta relasi item transaksi
        $transaksi = Transaksi::with(['TransaksiDetail.item','user'])
            ->findOrFail($id);

        // Format data untuk ditampilkan
        $data = [
            'header' => [
                'faktur'            => $transaksi->faktur,
                'tanggal'           => $transaksi->tanggal_transaksi,
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'diskon'            => $transaksi->diskon,
                'total_transaksi'   => $transaksi->total_transaksi,
                'total_bayar'       => $transaksi->total_bayar,
                'kembalian'         => $transaksi->kembalian,
                'status'            => $transaksi->status,
                'kasir'             => $transaksi->user->name ?? 'Unknown'
            ],
            'items' => $transaksi->TransaksiDetail->map(function ($item) {
                return [
                    'nama'          => $item->item->nama ?? 'Produk tidak ditemukan',
                    'harga'         => $item->harga_jual,
                    'jumlah'        => $item->jumlah,
                    'total_harga'   => $item->total_harga
                ];
            })
        ];

        return view('laporan.transaksi.detail', compact('data'));
    }
}
