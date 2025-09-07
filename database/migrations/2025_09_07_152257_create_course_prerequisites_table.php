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
        Schema::create('course_prerequisites', function (Blueprint $table) {
            $table->bigInteger('prerequisite_id', true);
            $table->bigInteger('course_id')->index('idx_course_id');
            $table->bigInteger('prerequisite_course_id')->index('idx_prerequisite_course_id');

            $table->unique(['course_id', 'prerequisite_course_id'], 'unique_course_prereq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_prerequisites');
    }
};
