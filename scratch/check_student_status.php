<?php

use App\Models\Mahasiswa;
use App\Models\Milestone;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mhs = Mahasiswa::with('tugasAkhir.milestones', 'tugasAkhir.bimbingans')->first();

if (!$mhs) {
    echo "No mahasiswa found.\n";
    exit;
}

echo "Mahasiswa: {$mhs->user->name}\n";
$ta = $mhs->tugasAkhir;

if (!$ta) {
    echo "No Tugas Akhir found.\n";
    exit;
}

echo "Milestones:\n";
foreach ($ta->milestones as $m) {
    echo "- {$m->jenis_milestone}: {$m->status}\n";
}

echo "\nBimbingans:\n";
foreach ($ta->bimbingans as $b) {
    echo "- ID: {$b->id}, Status: {$b->status}, Tanggal: {$b->tanggal}\n";
}
