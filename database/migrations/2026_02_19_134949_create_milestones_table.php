<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')
                  ->constrained('tugas_akhirs')
                  ->cascadeOnDelete();
            $table->string('jenis_milestone');
            $table->string('status')->default('pending');
            $table->date('deadline')->nullable();
            $table->string('file_path')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->dateTime('tanggal_upload')->nullable();
            $table->dateTime('tanggal_disetujui')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
