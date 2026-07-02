<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE stories ALTER COLUMN person_name TYPE json USING person_name::json');
        DB::statement('ALTER TABLE stories ALTER COLUMN person_name SET NOT NULL');
        DB::statement('ALTER TABLE stories ALTER COLUMN location TYPE json USING location::json');
        DB::statement('ALTER TABLE stories ALTER COLUMN location SET NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE stories ALTER COLUMN person_name TYPE varchar(191)');
        DB::statement('ALTER TABLE stories ALTER COLUMN person_name DROP NOT NULL');
        DB::statement('ALTER TABLE stories ALTER COLUMN location TYPE varchar(191)');
        DB::statement('ALTER TABLE stories ALTER COLUMN location DROP NOT NULL');
    }
};
