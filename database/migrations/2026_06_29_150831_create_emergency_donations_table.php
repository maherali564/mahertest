<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_campaign_id')->constrained()->cascadeOnDelete();
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('USD');
            $table->string('payment_method')->default('stripe');
            $table->string('payment_status')->default('completed');
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('emergency_campaign_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_donations');
    }
};
