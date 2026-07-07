<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_campaigns', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_campaigns', function (Blueprint $table) {
            $table->dropColumn('excerpt');
        });
    }
};
