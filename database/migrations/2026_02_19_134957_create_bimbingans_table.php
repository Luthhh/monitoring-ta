<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')
                  ->constrained('tugas_akhirs')
                  ->cascadeOnDelete();
            $table->foreignId('dosen_id')
                  ->constrained('dosens')
                  ->cascadeOnDelete();
            $table->dateTime('tanggal');
            $table->text('catatan')->nullable();
            $table->string('status')->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};
