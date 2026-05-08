<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $dosenRole = Role::where('name', 'dosen')->first();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        // ================= ADMIN =================
        User::create([
            'name' => 'Admin Monitoring',
            'email' => 'admin@apps.ipb.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        // ================= DOSEN =================
        $dosenUser = User::create([
            'name' => 'Dosen Pembimbing',
            'email' => 'dosen@apps.ipb.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $dosenRole->id,
        ]);

        $dosen = Dosen::create([
            'user_id' => $dosenUser->id,
            'nip' => '1987123456',
            'prodi' => 'Informatika',
            'foto_profil' => null,
        ]);

        // ================= MAHASISWA =================
        $mahasiswaUser = User::create([
            'name' => 'Mahasiswa TA',
            'email' => 'mahasiswa@apps.ipb.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $mahasiswaRole->id,
        ]);

        $mahasiswa = Mahasiswa::create([
            'user_id' => $mahasiswaUser->id,
            'nim' => '2020123456',
            'prodi' => 'Informatika',
            'tahun_masuk' => 2020,
            'semester' => 8,
            'pembimbing_id' => $dosen->id, // relasi ke dosen
        ]);

        TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => 'Sistem Monitoring Tugas Akhir Mahasiswa',
            'status' => 'Proses',
        ]);
    }
}