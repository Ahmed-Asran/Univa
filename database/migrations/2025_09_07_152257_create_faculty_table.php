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
        Schema::create('faculty', function (Blueprint $table) {
            $table->bigInteger('faculty_id', true);
            $table->bigInteger('user_id')->index('idx_user_id');
            $table->bigInteger('department_id')->nullable()->index('idx_department_id');
            $table->string('position', 100)->nullable();
            $table->boolean('is_deleted')->nullable()->default(false)->index('idx_is_deleted');

            $table->unique(['user_id'], 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};
