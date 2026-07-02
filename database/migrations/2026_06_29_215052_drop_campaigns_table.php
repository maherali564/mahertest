<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        if (Schema::hasColumn('donations', 'campaign_id')) {
            Schema::table('donations', fn (Blueprint $table) => $table->dropColumn('campaign_id'));
        }
        if (Schema::hasColumn('posts', 'campaign_id')) {
            Schema::table('posts', fn (Blueprint $table) => $table->dropColumn('campaign_id'));
        }

        Schema::enableForeignKeyConstraints();

        Schema::dropIfExists('campaigns');
    }

    public function down(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('goal_amount', 10, 2)->default(0);
            $table->decimal('raised_amount', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('slug')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
