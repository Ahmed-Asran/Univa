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
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->bigInteger('term_id', true);
            $table->string('term_name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->nullable()->default(false)->index('idx_is_current');
            $table->bigInteger('created_by')->nullable()->index('created_by');
            $table->bigInteger('updated_by')->nullable()->index('updated_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->index(['start_date', 'end_date'], 'idx_start_date_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
