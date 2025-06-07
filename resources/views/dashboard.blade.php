@extends('layout.app')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filter Laporan</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="tahun">Tahun</label>
                                <select class="form-select" id="tahun" name="tahun">
                                    @foreach(range(date('Y'), date('Y')-5) as $year)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="bulan">Bulan</label>
                                <select class="form-select" id="bulan" name="bulan">
                                    <option value="all">Semua Bulan</option>
                                    @foreach([
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 
                                        4 => 'April', 5 => 'Mei', 6 => 'Juni', 
                                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 
                                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ] as $num => $month)
                                        <option value="{{ $num }}" {{ $num == date('m') ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Pemasukan</h6>
                            <h3 class="mb-0 text-success" id="totalPemasukan">Rp 0</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-arrow-up text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                            <h3 class="mb-0 text-danger" id="totalPengeluaran">Rp 0</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-arrow-down text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Laba/Rugi</h6>
                            <h3 class="mb-0" id="labaRugi">Rp 0</h3>
                            <small id="labaRugiStatus" class="text-muted"></small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-chart-line text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Line Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Grafik Tren Keuangan</h5>
                </div>
                <div class="card-body">
                    <div id="lineChart" style="min-height: 450px;"></div>
                    <div id="chartLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Detail Laporan Keuangan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="financialTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-end">Pemasukan</th>
                                    <th class="text-end">Pengeluaran</th>
                                    <th class="text-end">Laba/Rugi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Data will be filled by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    $(document).ready(function() {
        let lineChart = null;
        
        // Load initial data
        loadDashboardData();

        // Form submission handler
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            loadDashboardData();
        });

        function loadDashboardData() {
            const tahun = $('#tahun').val();
            const bulan = $('#bulan').val();
            
            // Show loading state
            $('#chartLoading').show();
            $('#lineChart').hide();
            $('#financialTable').hide();

            $.ajax({
                url: '{{ route("dashboard.data") }}',
                type: 'GET',
                data: {
                    tahun: tahun,
                    bulan: bulan
                },
                success: function(response) {
                    // Update summary cards
                    updateSummaryCards(response.total);
                    
                    // Update chart and table
                    updateLineChart(response.monthlyData, tahun, bulan);
                    updateFinancialTable(response.monthlyData);
                    
                    // Hide loading state
                    $('#chartLoading').hide();
                    $('#lineChart').show();
                    $('#financialTable').show();
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    showErrorState();
                    $('#chartLoading').hide();
                }
            });
        }

        function updateSummaryCards(data) {
            $('#totalPemasukan').text('Rp ' + formatNumber(data.pemasukan));
            $('#totalPengeluaran').text('Rp ' + formatNumber(data.pengeluaran));
            
            const labaRugi = data.pemasukan - data.pengeluaran;
            const labaRugiElement = $('#labaRugi');
            const labaRugiStatus = $('#labaRugiStatus');
            
            labaRugiElement.text('Rp ' + formatNumber(Math.abs(labaRugi)));
            
            if (labaRugi > 0) {
                labaRugiElement.removeClass('text-danger').addClass('text-success');
                labaRugiStatus.text('(Laba)').removeClass('text-danger').addClass('text-success');
            } else if (labaRugi < 0) {
                labaRugiElement.removeClass('text-success').addClass('text-danger');
                labaRugiStatus.text('(Rugi)').removeClass('text-success').addClass('text-danger');
            } else {
                labaRugiElement.removeClass('text-danger text-success');
                labaRugiStatus.text('(Break Even)').removeClass('text-danger text-success');
            }
        }

        function updateLineChart(monthlyData, year, month) {
            const monthText = month === 'all' ? 'Semua Bulan' : $('#bulan option:selected').text();
            
            const categories = monthlyData.map(item => item.month);
            const pemasukanData = monthlyData.map(item => item.pemasukan);
            const pengeluaranData = monthlyData.map(item => item.pengeluaran);
            const labaData = monthlyData.map(item => (item.pemasukan - item.pengeluaran));

            // Destroy previous chart if exists
            if (lineChart) {
                lineChart.destroy();
            }
            
            // Create new line chart
            lineChart = Highcharts.chart('lineChart', {
                chart: {
                    type: 'line',
                    height: 450
                },
                title: {
                    text: `Tren Keuangan ${monthText} ${year}`
                },
                xAxis: {
                    categories: categories,
                    crosshair: true,
                    labels: {
                        rotation: -45
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah (Rp)'
                    },
                    labels: {
                        formatter: function() {
                            return 'Rp ' + formatNumberShort(this.value);
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    headerFormat: '<b>{point.key}</b><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>Rp {point.y:,.0f}</b></td></tr>',
                    footerFormat: '</table>'
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return 'Rp ' + formatNumberShort(this.y);
                            }
                        },
                        marker: {
                            radius: 5,
                            symbol: 'circle'
                        }
                    }
                },
                series: [{
                    name: 'Pemasukan',
                    data: pemasukanData,
                    color: '#28a745',
                    dashStyle: 'solid',
                    lineWidth: 3
                }, {
                    name: 'Pengeluaran',
                    data: pengeluaranData,
                    color: '#dc3545',
                    dashStyle: 'solid',
                    lineWidth: 3
                }, {
                    name: 'Laba/Rugi',
                    data: labaData,
                    color: '#17a2b8',
                    dashStyle: 'solid',
                    lineWidth: 4,
                    marker: {
                        symbol: 'diamond'
                    },
                    zoneAxis: 'y',
                    zones: [{
                        value: 0,
                        color: '#dc3545' // Warna untuk rugi
                    }, {
                        color: '#28a745' // Warna untuk laba
                    }]
                }],
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                        }
                    }
                }
            });
        }

        function updateFinancialTable(data) {
            const tableBody = $('#tableBody');
            tableBody.empty();
            
            data.forEach(item => {
                const labaRugi = item.pemasukan - item.pengeluaran;
                const status = labaRugi > 0 ? 
                    '<span class="badge bg-success">Laba</span>' : 
                    (labaRugi < 0 ? '<span class="badge bg-danger">Rugi</span>' : '<span class="badge bg-secondary">Break Even</span>');
                
                tableBody.append(`
                    <tr>
                        <td>${item.month}</td>
                        <td class="text-end">Rp ${formatNumber(item.pemasukan)}</td>
                        <td class="text-end">Rp ${formatNumber(item.pengeluaran)}</td>
                        <td class="text-end ${labaRugi >= 0 ? 'text-success' : 'text-danger'}">
                            ${labaRugi >= 0 ? '+' : '-'}Rp ${formatNumber(Math.abs(labaRugi))}
                        </td>
                        <td>${status}</td>
                    </tr>
                `);
            });
        }

        function showErrorState() {
            $('#totalPemasukan, #totalPengeluaran, #labaRugi').text('Error');
            $('#labaRugiStatus').text('(Error)').removeClass('text-success text-danger').addClass('text-muted');
            
            Highcharts.chart('lineChart', {
                title: { text: 'Gagal memuat data' },
                series: []
            });
            
            $('#tableBody').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>');
        }

        function formatNumber(num) {
            if (num === null || num === undefined) return '0';
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
        
        function formatNumberShort(num) {
            if (num === null || num === undefined) return '0';
            
            if (num >= 1000000000) {
                return (num / 1000000000).toFixed(1) + 'M';
            }
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'Jt';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }
    });
</script>
@endpush
@endsection