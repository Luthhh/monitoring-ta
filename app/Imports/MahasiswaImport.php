<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class MahasiswaImport implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{
    public $importedCount = 0;
    public $skippedCount = 0;
    public $errors = [];

    public function collection(Collection $rows)
    {
        $role = Role::where('name', 'mahasiswa')->first();
        $roleMahasiswaId = $role ? $role->id : null;

        foreach ($rows as $index => $row) {
            // Deteksi jika satu row isinya nyambung gara-gara delimiter nggak match
            if (!isset($row['nim']) && !isset($row['email'])) {
                // Cari index pertama (misal: "nim;nama;email")
                $firstKey = array_key_first($row->toArray());
                if ($firstKey && strpos($firstKey, ';') !== false) {
                    $this->errors[] = "Format CSV salah (menggunakan delimiter yang berbeda). Silakan gunakan Excel (.xlsx) atau sesuaikan delimiter menjadi koma/titik koma.";
                    break;
                }
            }

            $nim = $row['nim'] ?? null;
            $email = $row['email'] ?? null;
            $nama = $row['nama'] ?? $row['name'] ?? 'Mahasiswa';
            $prodi = $row['prodi'] ?? null;
            $tahunRaw = $row['tahun_masuk'] ?? null;
            $semester = $row['semester'] ?? 1;
            $password = $row['password'] ?? $nim;

            $tahun = null;
            if ($tahunRaw) {
                // If it contains a slash like "2022/2023", take the first 4 characters
                if (strpos((string)$tahunRaw, '/') !== false) {
                    $tahun = (int) trim(explode('/', (string)$tahunRaw)[0]);
                } else {
                    $tahun = (int) $tahunRaw;
                }
            }

            if (empty($nim) && empty($email)) {
                continue;
            }

            if (empty($nim) || empty($email)) {
                $this->skippedCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": NIM atau Email kosong.";
                continue;
            }

            if (!preg_match('/@apps\.ipb\.ac\.id$/i', $email)) {
                $this->skippedCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": Email {$email} tidak menggunakan domain institusi (@apps.ipb.ac.id).";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $this->skippedCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": Email {$email} sudah terdaftar.";
                continue;
            }

            if (Mahasiswa::where('nim', $nim)->exists()) {
                $this->skippedCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": NIM {$nim} sudah terdaftar.";
                continue;
            }

            $user = User::create([
                'name'     => $nama,
                'email'    => $email,
                'password' => Hash::make($password),
                'role_id'  => $roleMahasiswaId,
            ]);

            Mahasiswa::create([
                'user_id'     => $user->id,
                'nim'         => (string) $nim,
                'prodi'       => $prodi,
                'tahun_masuk' => $tahun,
                'semester'    => (int) $semester,
            ]);

            $this->importedCount++;
        }
    }

    public function getCsvSettings(): array
    {
        // Secara default PHP fputcsv pakai koma, tapi Excel di region Indo sering pakai titik koma
        // Coba kita biarkan default. Jika membaca CSV dengan koma gagal, 
        // kita sudah tangkap peringatan di loop dengan memeriksa if strpos($firstKey, ';')
        return [
            'delimiter' => ','
        ];
    }
}
