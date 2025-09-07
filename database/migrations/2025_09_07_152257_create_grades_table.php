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
        Schema::create('grades', function (Blueprint $table) {
            $table->bigInteger('grade_id', true);
            $table->bigInteger('enrollment_id')->index('idx_enrollment_id');
            $table->bigInteger('assignment_id')->index('idx_assignment_id');
            $table->decimal('points_earned', 6)->nullable();
            $table->timestamp('graded_date')->useCurrentOnUpdate()->useCurrent();
            $table->bigInteger('graded_by')->nullable()->index('graded_by');

            $table->unique(['enrollment_id', 'assignment_id'], 'unique_enrollment_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
