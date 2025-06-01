<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $transaksi;
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $metode_pembayaran;
    protected $rowNumber = 0;

    public function __construct($transaksi, $tanggal_awal, $tanggal_akhir, $metode_pembayaran)
    {
        $this->transaksi = $transaksi;
        $this->tanggal_awal = \Carbon\Carbon::parse($tanggal_awal)->translatedFormat('d F Y');
        $this->tanggal_akhir = \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y');
        $this->metode_pembayaran = $metode_pembayaran;
    }

    public function collection()
    {
        return $this->transaksi;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN TRANSAKSI'],
            ['Periode: ' . $this->tanggal_awal . ' s/d ' . $this->tanggal_akhir],
            ['Metode Pembayaran: ' . ($this->metode_pembayaran ? ucfirst($this->metode_pembayaran) : 'Semua Metode')],
            [],
            [
                'No',
                'No Faktur',
                'Tanggal Transaksi',
                'Metode Pembayaran',
                'Sub Total',
                'Diskon',
                'Total Transaksi',
                'Dibayar',
                'Kembalian',
                'Status'
            ]
        ];
    }

    public function map($transaksi): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $transaksi->faktur,
            \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->translatedFormat('d M Y'),
            ucfirst($transaksi->metode_pembayaran),
            'Rp ' . number_format($transaksi->sub_total ?? ($transaksi->diskon + $transaksi->total_transaksi), 0, ',', '.'),
            'Rp ' . number_format($transaksi->diskon, 0, ',', '.'),
            'Rp ' . number_format($transaksi->total_transaksi, 0, ',', '.'),
            'Rp ' . number_format($transaksi->total_bayar, 0, ',', '.'),
            'Rp ' . number_format($transaksi->kembalian, 0, ',', '.'),
            ucfirst($transaksi->status)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk judul laporan
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => 'center']
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center']
            ],
            3 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center']
            ],
            // Style untuk header kolom
            5 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD9D9D9']
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ]
            ],
            // Alignment untuk kolom angka
            'E:J' => [
                'alignment' => [
                    'horizontal' => 'right',
                    'vertical' => 'center'
                ]
            ],
            // Alignment untuk kolom teks
            'A:D' => [
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ]
            ],
            'A5:J5' => [
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Merge cell untuk judul laporan (A1:J1)
                $event->sheet->mergeCells('A1:J1');
                
                // Merge cell untuk periode (A2:J2)
                $event->sheet->mergeCells('A2:J2');
                
                // Merge cell untuk metode pembayaran (A3:J3)
                $event->sheet->mergeCells('A3:J3');
                
                // Set tinggi row untuk judul
                $event->sheet->getRowDimension(1)->setRowHeight(25);
                $event->sheet->getRowDimension(5)->setRowHeight(25); // Header kolom
                
                // Set border untuk data
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A5:J'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // ========== SET WIDTH KOLOM ==========
                $widths = [
                    'A' => 6,    // No
                    'B' => 25,  // No Faktur
                    'C' => 18,  // Tanggal Transaksi
                    'D' => 18,  // Metode Pembayaran
                    'E' => 15,  // Sub Total
                    'F' => 15,  // Diskon
                    'G' => 18,  // Total Transaksi
                    'H' => 15,  // Dibayar
                    'I' => 15,  // Kembalian
                    'J' => 15   // Status
                ];
                
                foreach ($widths as $column => $width) {
                    $event->sheet->getColumnDimension($column)->setWidth($width);
                }
            },
        ];
    }
}