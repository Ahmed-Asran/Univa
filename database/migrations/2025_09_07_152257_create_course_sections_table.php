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
        Schema::create('course_sections', function (Blueprint $table) {
            $table->bigInteger('section_id', true);
            $table->bigInteger('course_id');
            $table->bigInteger('term_id');
            $table->bigInteger('faculty_id')->nullable()->index('idx_faculty_id');
            $table->string('section_number', 10);
            $table->integer('current_enrollment')->nullable()->default(0);
            $table->text('content')->nullable();
            $table->boolean('is_deleted')->nullable()->default(false)->index('idx_is_deleted');

            $table->index(['term_id', 'course_id'], 'idx_term_id_course_id');
            $table->unique(['course_id', 'term_id', 'section_number'], 'unique_course_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_sections');
    }
};
