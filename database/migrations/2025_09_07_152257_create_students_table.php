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
        Schema::create('students', function (Blueprint $table) {
            $table->bigInteger('student_id')->primary();
            $table->bigInteger('user_id')->index('idx_user_id');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->decimal('current_gpa', 3)->nullable()->default(0);
            $table->integer('total_credits')->nullable()->default(0);
            $table->enum('level', ['First', 'Second', 'Third', 'Fourth', 'Graduated'])->nullable()->default('First')->index('idx_level');
            $table->boolean('is_deleted')->nullable()->default(false)->index('idx_is_deleted');

            $table->index(['student_id', 'user_id', 'level', 'current_gpa'], 'covering_idx');
            $table->unique(['user_id'], 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
