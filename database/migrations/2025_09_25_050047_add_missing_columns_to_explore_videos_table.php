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
        Schema::table('explore_videos', function (Blueprint $table) {
            // Add missing columns only if they don't exist
            if (!Schema::hasColumn('explore_videos', 'original_filename')) {
                $table->string('original_filename')->nullable()->after('description');
            }
            if (!Schema::hasColumn('explore_videos', 'file_size')) {
                $table->integer('file_size')->nullable()->after('original_filename');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('explore_videos', function (Blueprint $table) {
            $table->dropColumn(['original_filename', 'file_size']);
        });
    }
};