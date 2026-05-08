<?php

use App\Models\Bimbingan;
use Carbon\Carbon;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$now = Carbon::now();
$month = $now->month;
$year = $now->year;

$allBimbingans = Bimbingan::whereMonth('tanggal', $month)
    ->whereYear('tanggal', $year)
    ->get();

echo "Bimbingan Bulan Ini ($month/$year):\n";
echo "Total: " . $allBimbingans->count() . "\n";
echo "Selesai: " . $allBimbingans->where('status', 'selesai')->count() . "\n";
echo "Pending: " . $allBimbingans->where('status', 'pending')->count() . "\n";
echo "Disetujui: " . $allBimbingans->where('status', 'disetujui')->count() . "\n";
echo "Menunggu Verifikasi: " . $allBimbingans->where('status', 'menunggu_verifikasi')->count() . "\n";
echo "Ditolak: " . $allBimbingans->where('status', 'ditolak')->count() . "\n";

echo "\nDetail Bimbingan Non-Selesai:\n";
foreach ($allBimbingans->whereIn('status', ['pending', 'disetujui', 'menunggu_verifikasi']) as $b) {
    echo "ID: {$b->id}, Status: {$b->status}, Tanggal: {$b->tanggal}, Mahasiswa: " . ($b->tugasAkhir->mahasiswa->user->name ?? 'N/A') . "\n";
}
