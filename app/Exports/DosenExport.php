<?php

namespace App\Exports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DosenExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Dosen::with(['user'])->get()->map(function ($d, $i) {
            return [
                'No'    => $i + 1,
                'NIP'   => $d->nip,
                'Nama'  => $d->user->name ?? '-',
                'Email' => $d->user->email ?? '-',
                'Prodi' => $d->prodi ?? '-',
                'Total Mahasiswa Bimbingan' => \App\Models\Mahasiswa::where('pembimbing1_id', $d->id)
                    ->orWhere('pembimbing2_id', $d->id)->count(),
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'NIP', 'Nama', 'Email', 'Prodi', 'Total Mahasiswa Bimbingan'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                           'startColor' => ['rgb' => '02048D']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 18, 'C' => 28, 'D' => 30, 'E' => 20, 'F' => 25];
    }
}
