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
        Schema::table('emergency_campaigns', function (Blueprint $table) {
            $table->string('currency', 3)->default('SAR')->after('target_amount');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_campaigns', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
