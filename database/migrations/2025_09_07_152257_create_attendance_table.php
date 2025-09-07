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
        Schema::create('attendance', function (Blueprint $table) {
            $table->bigInteger('attendance_id', true);
            $table->bigInteger('enrollment_id');
            $table->date('class_date');
            $table->enum('status', ['Present', 'Absent', 'Late']);
            $table->timestamp('marked_at')->useCurrent();

            $table->index(['enrollment_id', 'class_date'], 'idx_enrollment_id_class_date');
            $table->index(['enrollment_id', 'class_date', 'status'], 'idx_enrollment_id_class_date_status');
            $table->unique(['enrollment_id', 'class_date'], 'unique_enrollment_class_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
