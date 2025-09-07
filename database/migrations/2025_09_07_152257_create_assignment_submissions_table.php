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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->bigInteger('submission_id', true);
            $table->bigInteger('assignment_id')->index('idx_assignment_id');
            $table->bigInteger('student_id')->index('idx_student_id');
            $table->text('submission_text')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->timestamp('submitted_at')->useCurrent();

            $table->unique(['assignment_id', 'student_id'], 'unique_assignment_student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
