<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('explore_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('prompt')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('video_url')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->string('resolution')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->json('tags')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['is_public', 'created_at']);
            $table->index(['views_count']);
            $table->index(['likes_count']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explore_videos');
    }
};


