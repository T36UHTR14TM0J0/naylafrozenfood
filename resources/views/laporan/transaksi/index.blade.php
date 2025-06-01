@extends('layout.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12 shadow shadow-sm p-3 bg-white rounded">
            <!-- ==================== FILTER FORM ==================== -->
            <form method="GET" action="{{ route('report.index') }}" class="mb-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                        <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ old('tanggal_awal', $tanggal_awal) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ old('tanggal_akhir', $tanggal_akhir) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="online" {{ request('metode_pembayaran') == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="{{ route('report.export_transaksi') }}?tanggal_awal={{ $tanggal_awal }}&tanggal_akhir={{ $tanggal_akhir }}&metode_pembayaran={{ request('metode_pembayaran') }}" 
                        class="btn btn-success btn-sm w-100">
                            <i class="fas fa-file-excel me-2"></i>Export
                        </a>
                    </div>
                </div>
            </form>
            

            <!-- ==================== TRANSACTION TABLE ==================== -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-white"><i class="fas fa-list-alt me-2"></i> Daftar Transaksi</h5>
                    <span class="badge bg-light text-dark fs-6">Total: {{ $totalTransactions }}</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if($transactions->isNotEmpty())
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>No Faktur</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Metode</th>
                                        <th class="text-center">Diskon</th>
                                        <th class="text-end">Total Transaksi</th>
                                        <th class="text-center">Status</th>
                                        <th width="6%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr class="align-middle">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ $transaction->faktur }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->locale('id')->translatedFormat('d M Y') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $transaction->metode_pembayaran === 'cash' ? 'success' : 'primary' }}">
                                                {{ ucfirst($transaction->metode_pembayaran) }}
                                            </span>
                                        </td>
                                        <td class="text-center">Rp {{ number_format($transaction->diskon,0,',','.') }}</td>
                                        <td class="text-end">Rp {{ number_format($transaction->total_transaksi, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $transaction->status === 'success' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('report.detail', $transaction->id) }}" class="btn btn-sm btn-primary px-2" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted fs-5">Tidak ada data transaksi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PAGINATION SECTION -->
            <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                <div class="text-muted">
                    Menampilkan <span class="fw-semibold">{{ $transactions->firstItem() }}</span> sampai <span class="fw-semibold">{{ $transactions->lastItem() }}</span> dari <span class="fw-semibold">{{ $transactions->total() }}</span> entri
                </div>
                <div>
                    {{ $transactions->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection