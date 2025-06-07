@extends('layout.app')
@section('title','Detail Laporan Harian')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('laporan.index', ['start_date' => $date, 'end_date' => $date]) }}" class="btn btn-secondary">
           Kembali
        </a>
        <a href="{{ route('laporan.cetak-harian', ['date' => $date]) }}" class="btn btn-danger" target="_blank">
        <i class="fas fa-file-pdf"></i> Cetak PDF
    </a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-white"> Tanggal: {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y') }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-success mb-3" style="min-height:150px !important;">
                        <div class="card-body">
                            <h5 class="card-title">Total Pemasukan</h5>
                            <p class="card-text fs-4 text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-danger mb-3" style="min-height:150px !important;">
                        <div class="card-body">
                            <h5 class="card-title">Total Pengeluaran</h5>
                            <p class="card-text fs-4 text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card {{ $labaRugi >= 0 ? 'border-info' : 'border-warning' }} mb-3" style="min-height:150px !important;">
                        <div class="card-body">
                            <h5 class="card-title">Laba/Rugi</h5>
                            <p class="card-text fs-4 {{ $labaRugi >= 0 ? 'text-info' : 'text-warning' }}">
                                Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}
                                <small class="d-block fs-6">({{ $labaRugi >= 0 ? 'Laba' : 'Rugi' }})</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0 text-white">Detail Pemasukan</h4>
                </div>
                <div class="card-body">
                    @if($transaksis->isEmpty())
                        <div class="alert alert-info">Tidak ada transaksi pada tanggal ini</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Faktur</th>
                                        <th>Waktu</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksis as $transaksi)
                                    <tr>
                                        <td>{{ $transaksi->faktur }}</td>
                                        <td>{{  \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->translatedFormat('d F Y ( H:i') . " WIB )"}}</td>
                                        <td class="text-end">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th class="text-end">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0 text-white">Detail Pengeluaran</h4>
                </div>
                <div class="card-body">
                    @if($pengeluarans->isEmpty())
                        <div class="alert alert-info">Tidak ada pengeluaran pada tanggal ini</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Item</th>
                                        <th>Supplier</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengeluarans as $pengeluaran)
                                    <tr>
                                        <td>{{  \Carbon\Carbon::parse($pengeluaran->created_at)->locale('id')->translatedFormat('d F Y ( H:i') . " WIB )" ?? '-' }}</td>
                                        <td>{{ $pengeluaran->item->nama ?? '-' }}</td>
                                        <td>{{ $pengeluaran->supplier->nama ?? '-' }}</td>
                                        <td class="text-end">{{ $pengeluaran->jumlah_stok }}</td>
                                        <td class="text-end">Rp {{ number_format($pengeluaran->harga, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($pengeluaran->jumlah_stok * $pengeluaran->harga, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="5">Total</th>
                                        <th class="text-end">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection