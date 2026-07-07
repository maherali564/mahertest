<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_donations', function (Blueprint $table) {
            if (!Schema::hasColumn('emergency_donations', 'stripe_session_id')) {
                $table->string('stripe_session_id', 255)->nullable()->unique()->after('converted_amount');
            }
            if (!Schema::hasColumn('emergency_donations', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id', 255)->nullable()->after('stripe_session_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('emergency_donations', function (Blueprint $table) {
            $table->dropColumn(['stripe_session_id', 'stripe_payment_intent_id']);
        });
    }
};
