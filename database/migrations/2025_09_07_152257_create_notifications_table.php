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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigInteger('notification_id', true);
            $table->bigInteger('user_id');
            $table->string('title', 200);
            $table->text('message');
            $table->enum('type', ['Email', 'SMS', 'Push']);
            $table->enum('priority', ['Low', 'Medium', 'High'])->nullable()->default('Medium')->index('idx_priority');
            $table->boolean('is_sent')->nullable()->default(false);
            $table->boolean('is_read')->nullable()->default(false);
            $table->timestamp('scheduled_for')->useCurrentOnUpdate()->useCurrent()->index('idx_scheduled_for');
            $table->integer('retry_count')->nullable()->default(0);
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent()->index('idx_created_at');
            $table->timestamp('sent_at')->default('0000-00-00 00:00:00');

            $table->index(['is_sent', 'created_at'], 'idx_is_sent_created_at');
            $table->index(['user_id', 'is_read'], 'idx_user_id_is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
