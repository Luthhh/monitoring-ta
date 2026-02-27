<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tugas_akhirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                  ->unique()
                  ->constrained('mahasiswas')
                  ->cascadeOnDelete();
            $table->string('judul');
            $table->string('status')->default('aktif');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_akhirs');
    }
};
