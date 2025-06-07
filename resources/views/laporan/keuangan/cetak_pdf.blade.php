<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - Naylafrozenfood</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 10pt;
        }
        
        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #1890FF;
            padding-bottom: 10px;
        }
        
        .company-name {
            color: #1890FF;
            font-size: 18pt;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            padding: 0;
        }
        
        .report-title {
            font-size: 14pt;
            font-weight: 600;
            margin: 5px 0;
            color: #444;
        }
        
        .report-period {
            font-size: 10pt;
            color: #666;
            margin: 0;
        }
        
        /* Summary Card */
        .summary-card {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
            padding: 10px;
            border-radius: 4px;
        }
        
        .summary-label {
            font-size: 9pt;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 12pt;
            font-weight: 700;
        }
        
        /* Table Styles */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9pt;
        }
        
        .report-table thead th {
            background-color: #1890FF;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
        }
        
        .report-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .report-table tfoot td {
            padding: 10px;
            font-weight: 600;
            background-color: #f5f5f5;
        }
        
        /* Text Utilities */
        .text-end {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-success {
            color: #28a745;
        }
        
        .text-danger {
            color: #dc3545;
        }
        
        .text-info {
            color: #17a2b8;
        }
        
        .text-warning {
            color: #ffc107;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 8pt;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 500;
        }
        
        .status-profit {
            background-color: #e6f7ff;
            color: #1890FF;
        }
        
        .status-loss {
            background-color: #fff2f0;
            color: #f5222d;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1 class="company-name">NAYLAFROZENFOOD</h1>
        <h2 class="report-title">Laporan Keuangan</h2>
        <p class="report-period">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>
    
    <!-- Summary Section -->
    <div class="summary-card">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">TOTAL PEMASUKAN</div>
                <div class="summary-value text-success">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">TOTAL PENGELUARAN</div>
                <div class="summary-value text-danger">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">TOTAL {{ $labaRugi >= 0 ? 'LABA' : 'RUGI' }}</div>
                <div class="summary-value {{ $labaRugi >= 0 ? 'text-info' : 'text-warning' }}">
                    Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-end">Pemasukan</th>
                <th class="text-end">Pengeluaran</th>
                <th class="text-end">Laba/Rugi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanHarian as $laporan)
            @php
                $labaRugiHarian = $laporan['pemasukan'] - $laporan['pengeluaran'];
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($laporan['tanggal'])->locale('id')->translatedFormat('d F Y') }}</td>
                <td class="text-end text-success">Rp {{ number_format($laporan['pemasukan'], 0, ',', '.') }}</td>
                <td class="text-end text-danger">Rp {{ number_format($laporan['pengeluaran'], 0, ',', '.') }}</td>
                <td class="text-end">
                    <div>Rp {{ number_format(abs($labaRugiHarian), 0, ',', '.') }}</div>
                    <span class="status-badge {{ $labaRugiHarian >= 0 ? 'status-profit' : 'status-loss' }}">
                        {{ $labaRugiHarian >= 0 ? 'Laba' : 'Rugi' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($pemasukan, 0, ',', '.') }}</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($pengeluaran, 0, ',', '.') }}</strong></td>
                <td class="text-end {{ $labaRugi >= 0 ? 'text-info' : 'text-warning' }}">
                    <strong>Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Footer -->
    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} | Naylafrozenfood Financial Report System
    </div>
</body>
</html>