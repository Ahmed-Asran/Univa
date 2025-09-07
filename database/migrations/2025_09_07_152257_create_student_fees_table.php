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
        Schema::create('student_fees', function (Blueprint $table) {
            $table->bigInteger('fee_id', true);
            $table->bigInteger('student_id');
            $table->bigInteger('fee_type_id')->index('fee_type_id');
            $table->decimal('amount', 10);
            $table->date('due_date')->index('idx_due_date');
            $table->enum('status', ['Pending', 'Paid', 'Overdue'])->nullable()->default('Pending');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['student_id', 'due_date', 'status'], 'idx_student_id_due_date_status');
            $table->index(['student_id', 'status'], 'idx_student_id_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_fees');
    }
};
