<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deadline_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('milestone_id')
                  ->constrained('milestones')
                  ->cascadeOnDelete();
            $table->date('old_deadline')->nullable();
            $table->date('new_deadline')->nullable();
            $table->foreignId('changed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deadline_change_logs');
    }
};
