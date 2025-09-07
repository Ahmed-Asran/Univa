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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->bigInteger('enrollment_id', true);
            $table->bigInteger('student_id');
            $table->bigInteger('section_id')->index('idx_section_id');
            $table->timestamp('enrollment_date')->useCurrent();
            $table->enum('status', ['Enrolled', 'Dropped', 'Completed'])->nullable()->default('Enrolled');
            $table->char('final_grade', 2)->nullable();
            $table->integer('result')->nullable();

            $table->index(['enrollment_id', 'student_id', 'section_id', 'status', 'final_grade'], 'covering_idx');
            $table->index(['student_id', 'section_id'], 'idx_student_id_section_id');
            $table->index(['student_id', 'status'], 'idx_student_id_status');
            $table->unique(['student_id', 'section_id'], 'unique_student_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
