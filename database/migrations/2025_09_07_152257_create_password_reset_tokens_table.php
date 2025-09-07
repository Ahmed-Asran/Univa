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
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->bigInteger('token_id', true);
            $table->bigInteger('user_id')->index('idx_user_id');
            $table->string('token')->index('idx_token');
            $table->timestamp('expires_at')->useCurrentOnUpdate()->useCurrent();
            $table->boolean('used')->nullable()->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['token'], 'token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
