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
        Schema::table('donations', function (Blueprint $table) {
            $table->integer('failed_attempts')->default(0)->after('status');
            $table->timestamp('last_attempt_at')->nullable()->after('failed_attempts');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['failed_attempts', 'last_attempt_at']);
        });
    }
};
