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
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigInteger('announcement_id', true);
            $table->string('title', 200);
            $table->text('content');
            $table->bigInteger('author_id')->index('idx_author_id');
            $table->bigInteger('course_section_id')->nullable()->index('idx_course_section_id');
            $table->boolean('is_published')->nullable()->default(true);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['is_published', 'created_at'], 'idx_is_published_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
