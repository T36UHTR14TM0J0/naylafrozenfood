<?php

namespace App\Http\Controllers;

use App\Models\StokItem;
use App\Models\Transaksi;
use App\Services\PdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LapKeuanganControllers extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Validasi tanggal
        if ($startDate > $endDate) {
            return redirect()->back()->with('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir');
        }
        
        // Data harian
        $laporanHarian = [];
        $currentDate = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);
        
        while ($currentDate <= $endDateObj) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Pemasukan harian
            $pemasukanHarian = Transaksi::where('status', 'success')
                ->whereDate('tanggal_transaksi', $dateStr)
                ->sum('total_transaksi');
                
            // Pengeluaran harian
            $pengeluaranHarian = StokItem::where('status', 'masuk')
                ->whereDate('created_at', $dateStr)
                ->sum(\DB::raw('jumlah_stok * harga'));
                
            $laporanHarian[] = [
                'tanggal' => $dateStr,
                'pemasukan' => $pemasukanHarian,
                'pengeluaran' => $pengeluaranHarian
            ];
            
            $currentDate->addDay();
        }
        
        // Hitung total
        $pemasukan = array_sum(array_column($laporanHarian, 'pemasukan'));
        $pengeluaran = array_sum(array_column($laporanHarian, 'pengeluaran'));
        $labaRugi = $pemasukan - $pengeluaran;
        
        return view('laporan.keuangan.index', compact(
            'laporanHarian',
            'pemasukan',
            'pengeluaran',
            'labaRugi',
            'startDate',
            'endDate'
        ));
    }

    public function detailHarian($date)
    {
        // Validasi format tanggal
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            abort(400, 'Format tanggal tidak valid');
        }

        $transaksis = Transaksi::where('status', 'success')
            ->whereDate('tanggal_transaksi', $date)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
            
        $pengeluarans = StokItem::with(['item', 'supplier'])
            ->where('status', 'masuk')
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalPemasukan = $transaksis->sum('total_transaksi');
        $totalPengeluaran = $pengeluarans->sum(function($item) {
            return $item->jumlah_stok * $item->harga;
        });
        $labaRugi = $totalPemasukan - $totalPengeluaran;
        
        return view('laporan.keuangan.detail_harian', compact(
            'transaksis',
            'pengeluarans',
            'totalPemasukan',
            'totalPengeluaran',
            'labaRugi',
            'date'
        ));
    }

    public function cetakHarian($date)
    {
        // Validasi format tanggal
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            abort(400, 'Format tanggal tidak valid');
        }

        // Ambil data sama seperti detailHarian
        $transaksis = Transaksi::where('status', 'success')
            ->whereDate('tanggal_transaksi', $date)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
            
        $pengeluarans = StokItem::with(['item', 'supplier'])
            ->where('status', 'masuk')
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalPemasukan = $transaksis->sum('total_transaksi');
        $totalPengeluaran = $pengeluarans->sum(function($item) {
            return $item->jumlah_stok * $item->harga;
        });
        $labaRugi = $totalPemasukan - $totalPengeluaran;
        
        $tanggal = Carbon::parse($date)->isoFormat('D MMMM Y');
        
            
        $html = view('laporan.keuangan.cetak_harian', compact(
            'transaksis',
            'pengeluarans',
            'totalPemasukan',
            'totalPengeluaran',
            'labaRugi',
            'date',
            'tanggal'
        ))->render();
        
        $pdfService = new PdfService();
        return $pdfService->generatePdf($html, 'Detail_Laporan_Harian-'.$tanggal.'.pdf');
    }

}
