<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Auto-generate slugs for existing stories based on title (ar locale)
        $stories = DB::table('stories')->whereNull('slug')->get();
        foreach ($stories as $story) {
            $title = json_decode($story->title, true);
            $text = $title['ar'] ?? $title['en'] ?? 'story-' . $story->id;
            $base = Str::slug($text, '-', 'ar');
            if (empty($base)) $base = 'story-' . $story->id;
            $slug = $base;
            $counter = 1;
            while (DB::table('stories')->where('slug', $slug)->where('id', '!=', $story->id)->exists()) {
                $slug = $base . '-' . $counter++;
            }
            DB::table('stories')->where('id', $story->id)->update(['slug' => $slug]);
        }

        // Make slug non-nullable after population
        Schema::table('stories', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
