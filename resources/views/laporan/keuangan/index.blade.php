@extends('layout.app')
@section('title', 'Laporan Keuangan')
@section('content')
<div class="container">    
    <div class="card mb-4">
        <div class="card-header">
            Filter Laporan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">Tanggal Awal</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>&nbsp;
                        <a href="{{ route('laporan.cetak-pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                        class="btn btn-danger ml-2" target="_blank">
                        Cetak
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rekapitulasi Harian</h5>
            <div>
                <span class="badge bg-success">Total Pemasukan: Rp {{ number_format($pemasukan, 0, ',', '.') }}</span>
                <span class="badge bg-danger ms-2">Total Pengeluaran: Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span>
                <span class="badge {{ $labaRugi >= 0 ? 'bg-info' : 'bg-warning' }} ms-2">
                    {{ $labaRugi >= 0 ? 'Laba' : 'Rugi' }}: Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th class="text-end">Pemasukan</th>
                            <th class="text-end">Pengeluaran</th>
                            <th class="text-end">Laba/Rugi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanHarian as $laporan)
                        @php
                            $labaRugiHarian = $laporan['pemasukan'] - $laporan['pengeluaran'];
                        @endphp
                        <tr>
                            <td>{{  \Carbon\Carbon::parse($laporan['tanggal'])->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-end text-success">Rp {{ number_format($laporan['pemasukan'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">Rp {{ number_format($laporan['pengeluaran'], 0, ',', '.') }}</td>
                            <td class="text-end {{ $labaRugiHarian >= 0 ? 'text-info' : 'text-warning' }}">
                                Rp {{ number_format(abs($labaRugiHarian), 0, ',', '.') }}
                                <small class="d-block text-muted">({{ $labaRugiHarian >= 0 ? 'Laba' : 'Rugi' }})</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('laporan.detail-harian', ['date' => $laporan['tanggal']]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end">Rp {{ number_format($pemasukan, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
                            <td class="text-end {{ $labaRugi >= 0 ? 'text-info' : 'text-warning' }}">
                                Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection