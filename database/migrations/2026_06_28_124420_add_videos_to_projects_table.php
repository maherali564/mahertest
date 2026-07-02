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
        Schema::table('projects', function (Blueprint $table) {
            $table->text('videos')->nullable()->after('video_type');
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->text('videos')->nullable()->after('video_type');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('videos');
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn('videos');
        });
    }
};
