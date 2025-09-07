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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->bigInteger('schedule_id', true);
            $table->bigInteger('section_id');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('classroom', 50)->nullable();

            $table->index(['section_id', 'day_of_week'], 'idx_section_id_day_of_week');
            $table->index(['start_time', 'end_time'], 'idx_start_time_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
