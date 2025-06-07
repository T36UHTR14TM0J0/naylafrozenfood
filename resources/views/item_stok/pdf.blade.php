<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 16px;font-weight: bold; margin-bottom: 10px; }
        .filter-info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; text-align: right; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN STOK BARANG</div>
        <div class="subtitle">Naylafrozenfood</div>
    </div>

    <div class="filter-info">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($tanggalAwal)->locale('id')->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggalAkhir)->locale('id')->translatedFormat('d F Y') }}<br>
        @if($status)
            <strong>Status:</strong> {{ ucfirst($status) }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Supplier</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Status</th>
                {{-- <th>Keterangan</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($productStocks as $key => $stock)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $stock->created_at->format('d/m/Y') }}</td>
                <td>{{ $stock->item->nama ?? '-' }}</td>
                <td>{{ $stock->supplier->nama ?? '-' }}</td>
                <td>{{ $stock->jumlah_stok }}</td>
                <td>Rp {{ number_format($stock['harga'], 0, ',', '.') }}</td>
                <td>{{ ucfirst($stock->status) }}</td>
                {{-- <td>{{ $stock->keterangan ?? '-' }}</td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>