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
        Schema::table('mahasiswas', function (Blueprint $table) {

            $table->unsignedBigInteger('pembimbing1_id')->nullable()->after('semester');
            $table->unsignedBigInteger('pembimbing2_id')->nullable()->after('pembimbing1_id');

            // Foreign key ke tabel dosens
            $table->foreign('pembimbing1_id')
                  ->references('id')
                  ->on('dosens')
                  ->nullOnDelete();

            $table->foreign('pembimbing2_id')
                  ->references('id')
                  ->on('dosens')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {

            $table->dropForeign(['pembimbing1_id']);
            $table->dropForeign(['pembimbing2_id']);

            $table->dropColumn(['pembimbing1_id', 'pembimbing2_id']);
        });
    }
};