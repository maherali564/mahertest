<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_donations', function (Blueprint $table) {
            $table->decimal('converted_amount', 10, 2)->nullable()->after('amount');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_donations', function (Blueprint $table) {
            $table->dropColumn('converted_amount');
            $table->dropIndex(['payment_status']);
        });
    }
};
