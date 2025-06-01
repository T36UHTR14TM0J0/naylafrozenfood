@extends('layout.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container">
    <!-- Header Transaksi -->
    <div class="row mb-4">
        <div class="col-md">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="text-light text-center">INFORMASI TRANSAKSI</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th colspan="2">No. Faktur</th>
                            <td colspan="3">{{ $data['header']['faktur'] }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Tanggal</th>
                            <td colspan="3">{{ \Carbon\Carbon::parse($data['header']['tanggal'])->locale('id')->translatedFormat('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Kasir</th>
                            <td colspan="3">{{ $data['header']['kasir'] }}</td>
                        </tr>
                        <tr>
                          <td colspan="5" style="padding: 10px;">
                          </td>
                        </tr>
                        <tr class="bg-primary">
                          <th colspan="5" class="text-center text-light">PEMBAYARAN</th>
                        </tr>
                        <tr>
                            <th colspan="2">Metode</th>
                            <td colspan="3">
                                <span class="badge bg-{{ $data['header']['metode_pembayaran'] == 'cash' ? 'success' : 'info' }}">
                                    {{ ucfirst($data['header']['metode_pembayaran']) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">Status</th>
                            <td colspan="3">
                                <span class="badge bg-{{ $data['header']['status'] === 'success' ? 'success' : ($data['header']['status'] === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($data['header']['status']) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                          <td colspan="5" style="padding: 10px;">
                          </td>
                        </tr>
                        <tr class="bg-primary">
                          <th colspan="5" class="text-center text-light">DAFTAR ITEM</th>
                        </tr>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Item</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Total Harga</th>
                        </tr>
                         <?php $subtotal = 0 ;?>
                         @foreach($data['items'] as $index => $item)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td class="text-end">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item['jumlah'] }}</td>
                            <td class="text-end">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                        </tr>
                        <?php

                          $subtotal += $item['total_harga'];
                        ?>
                        @endforeach
                        <tr>
                          <td colspan="5" style="padding: 10px;">
                          </td>
                        </tr>
                        <tr class="bg-primary">
                          <th colspan="5" class="text-center text-light">RINGKASAN PEMBAYARAN</th>
                        </tr>
                        <tr>
                            <th colspan="2">Sub Total</th>
                            <td colspan="5" class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Diskon</th>
                            <td colspan="5" class="text-end">- Rp {{ number_format($data['header']['diskon'], 0, ',', '.') }}</td>
                        </tr>
                        <tr class="table-active">
                            <th colspan="2">Total Transaksi</th>
                            <td colspan="5" class="text-end fw-bold">Rp {{ number_format($data['header']['total_transaksi'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Dibayar</th>
                            <td colspan="5" class="text-end">Rp {{ number_format($data['header']['total_bayar'], 0, ',', '.') }}</td>
                        </tr>
                        <tr class="table-success">
                            <th colspan="2">Kembalian</th>
                            <td colspan="5" class="text-end fw-bold">Rp {{ number_format($data['header']['kembalian'], 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Tombol Aksi -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('report.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
        <a href="{{ route('report.cetak_pdf', $data['header']['id']) }}" class="btn btn-primary" target="_blank">
            <i class="fas fa-print me-2"></i> Cetak PDF
        </a>
    </div>
</div>
@endsection