<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MahasiswaExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $collection;

    public function __construct($collection = null)
    {
        $this->collection = $collection ?: Mahasiswa::with(['user', 'pembimbing1.user', 'pembimbing2.user', 'tugasAkhir.milestones'])->get();
    }

    public function collection()
    {
        return $this->collection->map(function ($mhs, $i) {
            // Determine Academic Status (Doughnut labels logic)
            $isLulus = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0;
            
            $status = 'Tidak Tepat Waktu';
            if ($isLulus && $mhs->semester <= 4) {
                $status = 'Tepat Waktu';
            } elseif (!$isLulus && $mhs->semester >= 1 && $mhs->semester <= 3) {
                $status = 'Dalam Proses';
            } elseif (!$isLulus && $mhs->semester == 7) {
                $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                if (!$hasSeminar) $status = 'Terancam DO';
            }

            return [
                'No'            => $i + 1,
                'NIM'           => $mhs->nim,
                'Nama'          => $mhs->user->name ?? '-',
                'Email'         => $mhs->user->email ?? '-',
                'Prodi'         => $mhs->prodi ?? '-',
                'Tahun Masuk'   => $mhs->tahun_masuk ? $mhs->tahun_masuk . '/' . ($mhs->tahun_masuk + 1) . ' Ganjil' : '-',
                'Semester'      => $mhs->semester ?? '-',
                'Status Akdemik' => $status,
                'Pembimbing 1'  => optional($mhs->pembimbing1)->user->name ?? '-',
                'Pembimbing 2'  => optional($mhs->pembimbing2)->user->name ?? '-',
                'Judul TA'      => optional($mhs->tugasAkhir)->judul ?? '-',
                'Status TA'     => optional($mhs->tugasAkhir)->status ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'NIM', 'Nama', 'Email', 'Prodi', 'Angkatan', 'Semester', 'Status Akademik',
                'Pembimbing 1', 'Pembimbing 2', 'Judul TA', 'Status TA'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '02048D'],
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, 'B' => 15, 'C' => 25, 'D' => 28,
            'E' => 15, 'F' => 18, 'G' => 10, 'H' => 18,
            'I' => 25, 'J' => 25, 'K' => 45, 'L' => 12,
        ];
    }
}
