<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 20px;
            width: 100%;
        }
        .summary td {
            border: none;
            padding: 5px;
        }
        .summary .label {
            font-weight: bold;
            width: 30%;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN HARIAN</h2>
        <p>Tanggal: {{ $tanggal }}</p>
        <p>Dicetak pada: {{ now()->isoFormat('D MMMM Y H:mm:ss') }}</p>
    </div>

    <h3>Pemasukan</h3>
    @if($transaksis->isEmpty())
        <p>Tidak ada transaksi</p>
    @else
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">No. Faktur</th>
                    <th width="15%">Waktu</th>
                    <th width="15%" class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $key => $transaksi)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $transaksi->faktur }}</td>
                    <td>{{ $transaksi->tanggal_transaksi }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total Pemasukan</th>
                    <th class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @endif

    <h3 style="margin-top: 20px;">Pengeluaran</h3>
    @if($pengeluarans->isEmpty())
        <p>Tidak ada pengeluaran</p>
    @else
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Item</th>
                    <th width="20%">Supplier</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluarans as $key => $pengeluaran)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $pengeluaran->item->nama ?? '-' }}</td>
                    <td>{{ $pengeluaran->supplier->nama ?? '-' }}</td>
                    <td class="text-right">{{ $pengeluaran->jumlah_stok }}</td>
                    <td class="text-right">Rp {{ number_format($pengeluaran->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($pengeluaran->jumlah_stok * $pengeluaran->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total Pengeluaran</th>
                    <th class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @endif

    <table class="summary">
        <tr>
            <td class="label">Total Pemasukan</td>
            <td class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Pengeluaran</td>
            <td class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Laba/Rugi</td>
            <td class="text-right">
                Rp {{ number_format(abs($labaRugi), 0, ',', '.') }} 
                ({{ $labaRugi >= 0 ? 'Laba' : 'Rugi' }})
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name ?? 'System' }}</p>
    </div>
</body>
</html>