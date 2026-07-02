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
        Schema::table('emergency_donations', function (Blueprint $table) {
            $table->string('donor_country', 100)->nullable()->after('is_anonymous');
            $table->string('donor_city', 100)->nullable()->after('donor_country');
            $table->decimal('donor_latitude', 10, 7)->nullable()->after('donor_city');
            $table->decimal('donor_longitude', 10, 7)->nullable()->after('donor_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_donations', function (Blueprint $table) {
            $table->dropColumn(['donor_country', 'donor_city', 'donor_latitude', 'donor_longitude']);
        });
    }
};
