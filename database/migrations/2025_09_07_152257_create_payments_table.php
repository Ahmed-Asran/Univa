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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigInteger('payment_id', true);
            $table->bigInteger('student_id');
            $table->bigInteger('fee_id')->index('fee_id');
            $table->decimal('amount', 10);
            $table->string('payment_method', 50);
            $table->string('transaction_reference', 100)->nullable();
            $table->timestamp('payment_date')->useCurrent();
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->nullable()->default('Pending');

            $table->index(['student_id', 'payment_date'], 'idx_student_id_payment_date');
            $table->index(['student_id', 'status', 'payment_date'], 'idx_student_id_status_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
