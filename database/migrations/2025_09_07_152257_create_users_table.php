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
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('user_id', true);
            $table->string('username', 50)->index('idx_username');
            $table->string('email', 100)->unique('email');
            $table->string('password_hash');
            $table->string('name', 50);
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('is_deleted')->nullable()->default(false)->index('idx_is_deleted');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->index(['user_id', 'username', 'email', 'is_active'], 'covering_idx');
            $table->index(['email'], 'idx_email');
            $table->index(['is_active', 'created_at'], 'idx_is_active_created_at');
            $table->unique(['username'], 'username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
