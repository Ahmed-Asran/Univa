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
        Schema::create('courses', function (Blueprint $table) {
            $table->bigInteger('course_id', true);
            $table->string('course_code', 20)->unique('course_code');
            $table->string('course_name', 200);
            $table->text('description')->nullable();
            $table->integer('credit_hours');
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('is_deleted')->nullable()->default(false)->index('idx_is_deleted');
            $table->bigInteger('created_by')->nullable()->index('created_by');
            $table->bigInteger('updated_by')->nullable()->index('updated_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->index(['course_id', 'course_code', 'course_name', 'credit_hours', 'is_active'], 'covering_idx');
            $table->index(['course_code'], 'idx_course_code');
            $table->index(['is_active', 'course_code'], 'idx_is_active_course_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
