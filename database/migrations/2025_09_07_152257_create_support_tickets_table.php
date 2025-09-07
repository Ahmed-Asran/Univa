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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->bigInteger('ticket_id', true);
            $table->bigInteger('student_id');
            $table->string('subject', 200);
            $table->text('description');
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed'])->nullable()->default('Open');
            $table->timestamp('created_at')->useCurrent()->index('idx_created_at');
            $table->timestamp('resolved_at')->default('0000-00-00 00:00:00');

            $table->index(['student_id', 'status'], 'idx_student_id_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
