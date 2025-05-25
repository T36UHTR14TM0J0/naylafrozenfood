@extends('layout.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12 shadow shadow-sm p-2">
            <!-- ==================== FILTER FORM ==================== -->
            <form method="GET" action="{{ route('report.index') }}" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                        <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ old('tanggal_awal', $tanggal_awal) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ old('tanggal_akhir', $tanggal_akhir) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-control">
                            <option value="">Pilih</option>
                            <option value="cash" {{ request('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="online" {{ request('metode_pembayaran') == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <!-- ==================== TRANSACTION TABLE ==================== -->
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white"><i class="bi bi-list-ul"></i> Daftar Transaksi</h5>
                    <span>Total Transaksi: {{ $totalTransactions }}</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive p-4" style="max-height: 600px; overflow-y: auto;">
                        @if($transactions->isNotEmpty())
                            <table class="table align-middle table-hover table-bordered">
                                <thead class="bg-light text-white">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">No Faktur</th>
                                        <th class="text-center">Tanggal Transaksi</th>
                                        <th class="text-center">Metode Pembayaran</th>
                                        <th class="text-center">Total Transaksi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;?>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="text-center"><?= $no++;?></td>
                                        <td>{{ $transaction->faktur }}</td>
                                        <td  class="text-center">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->locale('id')->translatedFormat('d F Y') }}</td>
                                        <td  class="text-center">
                                            <span class="badge bg-{{ $transaction->metode_pembayaran === 'cash' ? 'success' : 'primary' }}">
                                                {{ ucfirst($transaction->metode_pembayaran) }}
                                            </span>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($transaction->total_transaksi, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $transaction->status === 'success' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>
                                          <a href="{{ route('report.detail', $transaction->id) }}" class="btn btn-sm btn-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center py-4">Transaksi tidak ditemukan.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PAGINATION SECTION -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $transactions->firstItem() }} sampai {{ $transactions->lastItem() }} dari {{ $transactions->total() }} entri
                </div>
                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
