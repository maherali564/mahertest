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
            $table->string('target_country', 100)->default('فلسطين')->after('image');
            $table->string('target_country_code', 2)->default('PS')->after('target_country');
            $table->string('target_flag', 10)->default('🇵🇸')->after('target_country_code');
            $table->decimal('target_latitude', 10, 7)->default(31.5)->after('target_flag');
            $table->decimal('target_longitude', 10, 7)->default(34.5)->after('target_latitude');
            $table->string('target_location', 255)->default('فلسطين - غزة')->after('target_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'target_country', 'target_country_code', 'target_flag',
                'target_latitude', 'target_longitude', 'target_location',
            ]);
        });
    }
};
