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
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('text_color', 20)->nullable()->after('button_link');
            $table->string('text_alignment', 20)->default('center')->after('text_color');
            $table->string('button_color', 20)->nullable()->after('text_alignment');
            $table->string('button_text_color', 20)->nullable()->after('button_color');
            $table->tinyInteger('overlay_opacity')->unsigned()->default(45)->after('button_text_color');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['text_color', 'text_alignment', 'button_color', 'button_text_color', 'overlay_opacity']);
        });
    }
};
