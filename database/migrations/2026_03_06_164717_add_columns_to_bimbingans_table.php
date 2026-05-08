<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->string('tahun_semester')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('durasi_jam')->nullable();
            $table->string('tipe_penyelenggaraan')->nullable();
            $table->string('nama_dokumen')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->string('link_kegiatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropColumn([
                'tahun_semester', 'nama_kegiatan', 'tanggal_selesai',
                'durasi_jam', 'tipe_penyelenggaraan', 'nama_dokumen',
                'file_dokumen', 'link_kegiatan'
            ]);
        });
    }
};
