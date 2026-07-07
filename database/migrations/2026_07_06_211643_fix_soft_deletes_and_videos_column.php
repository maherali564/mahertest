<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add soft deletes to tables that are missing them
        foreach (['donations', 'volunteers', 'projects', 'stories'] as $table) {
            if (!Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, fn (Blueprint $t) => $t->softDeletes());
            }
        }

        // Change videos column from text to json in projects and stories
        $driver = DB::getDriverName();
        foreach (['projects', 'stories'] as $table) {
            if (Schema::hasColumn($table, 'videos')) {
                if ($driver === 'pgsql') {
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"videos\" TYPE json USING \"videos\"::json");
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"videos\" DROP NOT NULL");
                } elseif ($driver === 'mysql') {
                    DB::statement("ALTER TABLE `{$table}` MODIFY `videos` JSON NULL");
                } else {
                    Schema::table($table, fn (Blueprint $t) => $t->json('videos')->nullable()->change());
                }
            }
        }

        // Add created_at to currency_rates if missing
        if (Schema::hasTable('currency_rates') && !Schema::hasColumn('currency_rates', 'created_at')) {
            Schema::table('currency_rates', fn (Blueprint $t) => $t->timestamp('created_at')->nullable()->after('updated_at'));
        }
    }

    public function down(): void
    {
        foreach (['donations', 'volunteers', 'projects', 'stories'] as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropSoftDeletes());
            }
        }
    }
};
