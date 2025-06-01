<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $data['header']['faktur'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bg-primary {
            background-color: #0d6efd !important;
            color: white;
        }
        .bg-success {
            background-color: #198754 !important;
            color: white;
        }
        .bg-dark {
            background-color: #212529 !important;
            color: white;
        }
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        .bg-success {
            background-color: #198754;
            color: white;
        }
        .bg-warning {
            background-color: #ffc107;
            color: black;
        }
        .bg-danger {
            background-color: #ff0000;
            color: rgb(255, 255, 255);
        }
        .bg-info {
            background-color: #0dcaf0;
            color: white;
        }
        .table-active {
            background-color: rgba(0,0,0,0.05);
        }
        .fw-bold {
            font-weight: bold;
        }
        .card {
            border: 1px solid rgba(0,0,0,0.125);
            border-radius: 0.25rem;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            border-bottom: 1px solid rgba(0,0,0,0.125);
        }
        .card-body {
            padding: 1.25rem;
        }
        .text-light {
            color: rgb(0, 0, 0) !important;
        }
    </style>
</head>
<body>
    <h4 style="text-align: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">DETAIL TRANSAKSI</h4>
    
    <div style="margin-bottom: 20px;">
        <div style="border: 1px solid rgba(0,0,0,0.125); border-radius: 0.25rem;">
            <div style="background-color: #212529; color: white; padding: 0.75rem; text-align: center;">
                <h5 style="margin: 0; color: white;">INFORMASI TRANSAKSI</h5>
            </div>
            <div style="padding: 1.25rem;">
                <table class="table">
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
                        <td colspan="5" style="padding: 10px;"></td>
                    </tr>
                    <tr class="bg-primary">
                        <th colspan="5" class="text-center text-light"><center>PEMBAYARAN</center></th>
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
                </table>
                
                <table class="table">
                    <tr class="bg-primary">
                        <th colspan="5" class="text-center text-light"><center>DAFTAR ITEM</center></th>
                    </tr>
                    <tr>
                        <th width="5%">#</th>
                        <th><center>Nama Item</center></th>
                        <th class="text-end"><center>Harga</center></th>
                        <th class="text-center"><center>Jumlah</center></th>
                        <th class="text-end"><center>Total Harga</center></th>
                    </tr>
                    <?php $subtotal = 0; ?>
                    @foreach($data['items'] as $index => $item)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td class="text-end">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item['jumlah'] }}</td>
                        <td class="text-end">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                    </tr>
                    <?php $subtotal += $item['total_harga']; ?>
                    @endforeach
                </table>
                
                <table class="table">
                    <tr class="bg-primary">
                        <th colspan="5" class="text-center text-light"><center>RINGKASAN PEMBAYARAN</center></th>
                    </tr>
                    <tr>
                        <th colspan="2">Sub Total</th>
                        <td colspan="3" class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Diskon</th>
                        <td colspan="3" class="text-end">- Rp {{ number_format($data['header']['diskon'], 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-active">
                        <th colspan="2">Total Transaksi</th>
                        <td colspan="3" class="text-end fw-bold">Rp {{ number_format($data['header']['total_transaksi'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Dibayar</th>
                        <td colspan="3" class="text-end">Rp {{ number_format($data['header']['total_bayar'], 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background-color: #d1e7dd;">
                        <th colspan="2">Kembalian</th>
                        <td colspan="3" class="text-end fw-bold">Rp {{ number_format($data['header']['kembalian'], 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>