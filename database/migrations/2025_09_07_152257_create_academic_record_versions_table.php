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
        Schema::create('academic_record_versions', function (Blueprint $table) {
            $table->bigInteger('version_id', true);
            $table->bigInteger('enrollment_id')->index('idx_enrollment_id');
            $table->char('grade', 2)->nullable();
            $table->timestamp('changed_at')->useCurrent()->index('idx_changed_at');
            $table->bigInteger('changed_by')->index('changed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_record_versions');
    }
};
