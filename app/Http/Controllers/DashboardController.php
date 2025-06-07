<?php

namespace App\Http\Controllers;

use App\Models\StokItem;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard');
    }

    public function getDashboardData(Request $request)
    {
        // Get filter parameters
        $year = $request->input('tahun', date('Y'));
        $month = $request->input('bulan', 'all');

        // Initialize query builders
        $pemasukanQuery = Transaksi::where('status', 'success')
            ->whereYear('tanggal_transaksi', $year);
            
        $pengeluaranQuery = StokItem::where('status', 'masuk')
            ->whereYear('created_at', $year);

        // Apply month filter if not 'all'
        if ($month !== 'all') {
            $pemasukanQuery->whereMonth('tanggal_transaksi', $month);
            $pengeluaranQuery->whereMonth('created_at', $month);
        }

        // Get total for the period
        $totalPemasukan = $pemasukanQuery->sum('total_transaksi');
        $totalPengeluaran = $pengeluaranQuery->sum(DB::raw('jumlah_stok * harga'));
        $laba = $totalPemasukan - $totalPengeluaran;

        // Prepare monthly data for charts
        $months = $month === 'all' ? range(1, 12) : [$month];
        $monthlyData = [];

        foreach ($months as $m) {
            $monthPemasukan = Transaksi::where('status', 'success')
                ->whereYear('tanggal_transaksi', $year)
                ->whereMonth('tanggal_transaksi', $m)
                ->sum('total_transaksi');

            $monthPengeluaran = StokItem::where('status', 'masuk')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->sum(DB::raw('jumlah_stok * harga'));

            $monthlyData[] = [
                'month' => date('F', mktime(0, 0, 0, $m, 1)),
                'pemasukan' => $monthPemasukan,
                'pengeluaran' => $monthPengeluaran,
                'laba' => $monthPemasukan - $monthPengeluaran
            ];
        }

        return response()->json([
            'total' => [
                'pemasukan' => $totalPemasukan,
                'pengeluaran' => $totalPengeluaran,
                'laba' => $laba
            ],
            'monthlyData' => $monthlyData,
            'year' => $year,
            'month' => $month === 'all' ? 'Semua Bulan' : date('F', mktime(0, 0, 0, $month, 1))
        ]);
    }

}
