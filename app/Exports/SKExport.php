<?php

namespace App\Exports;

use App\Models\SK;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SKExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $year;
    protected $month;
    protected $q;
    protected $nomor;

    public function __construct($year = null, $month = null, $q = null, $nomor = null)
    {
        $this->year  = $year;
        $this->month = $month;
        $this->q     = $q;
        $this->nomor = $nomor;
    }

    public function collection()
    {
        $query = SK::query();

        // Filter tahun
        if (!empty($this->year)) {
            $query->whereYear('tanggal_ditetapkan', $this->year);
        }

        // Filter bulan (bisa "01".."12" atau "2025-10")
        if (!empty($this->month)) {
            $month = $this->month;

            // kalau format "2025-10" → ambil "10"
            if (strlen($month) > 2) {
                $month = substr($month, -2);
            }

            $query->whereMonth('tanggal_ditetapkan', $month);
        }

        // Filter pencarian global
        if (!empty($this->q)) {
            $search = $this->q;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sk', 'like', "%{$search}%")
                    ->orWhere('jenis_surat', 'like', "%{$search}%")
                    ->orWhere('pembuat', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Filter nomor SK spesifik
        if (!empty($this->nomor)) {
            $query->where('nomor_sk', 'like', "%{$this->nomor}%");
        }

        return $query
            ->orderByRaw('CAST(nomor_sk AS UNSIGNED) ASC')
            ->get([
                'nomor_sk',
                'jenis_surat',
                'tanggal_ditetapkan',
                'pejabat_penandatangan',
                'perihal',
            ]);
    }

    public function headings(): array
    {
        return [
            'Nomor SK',
            'Jenis Surat',
            'Tanggal Ditetapkan',
            'Pembuat',
            'Perihal',
        ];
    }

    public function map($sk): array
    {
        return [
            $sk->nomor_sk,
            $sk->jenis_surat,
            $sk->tanggal_ditetapkan
                ? $sk->tanggal_ditetapkan->format('d-m-Y')
                : '',
            $sk->pejabat_penandatangan,
            $sk->perihal,
        ];
    }

    /**
     * Styling untuk sheet (header biru muda, tanggal rata tengah).
     */
    public function styles(Worksheet $sheet)
    {
        // Header (baris 1): warna biru muda + bold
        $sheet->getStyle('A1:E1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFCCE5FF'); // biru muda (ARGB: FF + CCE5FF)

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Rata tengah kolom tanggal (kolom C)
        $sheet->getStyle('C:C')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // (opsional) Rata vertikal tengah semua sel
        $sheet->getStyle('A:Z')->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        // ShouldAutoSize akan mengurus lebar kolom → tidak berdempetan
        return [];
    }
}