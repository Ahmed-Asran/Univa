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
        Schema::create('assignments', function (Blueprint $table) {
            $table->bigInteger('assignment_id', true);
            $table->bigInteger('section_id')->index('idx_section_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->timestamp('due_date')->useCurrentOnUpdate()->useCurrent();
            $table->enum('type', ['Quiz', 'Assignment', 'Project']);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['section_id', 'due_date'], 'idx_section_id_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
