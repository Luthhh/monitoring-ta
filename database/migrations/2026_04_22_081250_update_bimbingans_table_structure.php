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
            $table->time('waktu')->nullable()->after('tanggal');
            $table->string('tempat')->nullable()->after('waktu');
            $table->text('deskripsi')->nullable()->after('tempat');
            $table->text('hasil_bimbingan')->nullable()->after('deskripsi');
            $table->text('catatan_mahasiswa')->nullable()->after('hasil_bimbingan');
        });
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropColumn(['waktu', 'tempat', 'deskripsi', 'hasil_bimbingan', 'catatan_mahasiswa']);
        });
    }
};
