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
        Schema::create('course_materials', function (Blueprint $table) {
            $table->bigInteger('material_id', true);
            $table->bigInteger('section_id')->index('idx_section_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('type', ['LectureNotes', 'Assignment', 'Exam', 'Other']);
            $table->string('file_path', 500)->nullable();
            $table->bigInteger('uploaded_by')->index('idx_uploaded_by');
            $table->timestamp('upload_date')->useCurrent();

            $table->index(['section_id', 'type'], 'idx_section_id_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};
