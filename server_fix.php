<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$batch = DB::table('migrations')->max('batch') ?? 0;
$nextBatch = $batch + 1;

$exists = DB::table('migrations')->where('migration', '2026_06_29_131536_fix_stories_json_columns')->exists();
if (!$exists) {
    DB::table('migrations')->insert([
        'migration' => '2026_06_29_131536_fix_stories_json_columns',
        'batch' => $nextBatch,
    ]);
    echo "Marked fix_stories_json_columns as run (batch $nextBatch)\n";
}

$indexes = [
    // donations (skip if index already exists)
    "ALTER TABLE donations ADD INDEX donations_status_index(status)",
    "ALTER TABLE donations ADD INDEX donations_email_index(email)",
    "ALTER TABLE donations ADD INDEX donations_project_id_index(project_id)",
    "ALTER TABLE donations ADD INDEX donations_story_id_index(story_id)",
    "ALTER TABLE donations ADD INDEX donations_donor_id_index(donor_id)",
    "ALTER TABLE donations ADD INDEX donations_created_at_index(created_at)",
    "ALTER TABLE donations ADD INDEX donations_donated_at_index(donated_at)",
    "ALTER TABLE donations ADD INDEX donations_status_created_at_index(status, created_at)",
    "ALTER TABLE donations ADD INDEX donations_status_project_id_index(status, project_id)",
    "ALTER TABLE donations ADD INDEX donations_status_story_id_index(status, story_id)",
    // projects
    "ALTER TABLE projects ADD INDEX projects_is_active_index(is_active)",
    "ALTER TABLE projects ADD INDEX projects_sort_order_index(sort_order)",
    "ALTER TABLE projects ADD INDEX projects_is_active_sort_order_index(is_active, sort_order)",
    // stories (no slug column)
    "ALTER TABLE stories ADD INDEX stories_is_active_index(is_active)",
    "ALTER TABLE stories ADD INDEX stories_sort_order_index(sort_order)",
    // posts
    "ALTER TABLE posts ADD INDEX posts_is_active_index(is_active)",
    "ALTER TABLE posts ADD INDEX posts_type_index(type)",
    "ALTER TABLE posts ADD INDEX posts_published_at_index(published_at)",
    // contact_submissions
    "ALTER TABLE contact_submissions ADD INDEX contact_submissions_is_read_index(is_read)",
    // volunteers
    "ALTER TABLE volunteers ADD INDEX volunteers_status_index(status)",
    // donation_submissions
    "ALTER TABLE donation_submissions ADD INDEX donation_submissions_status_index(status)",
    // statistics
    "ALTER TABLE statistics ADD INDEX statistics_type_index(type)",
    "ALTER TABLE statistics ADD INDEX statistics_type_is_active_index(type, is_active)",
    // programs
    "ALTER TABLE programs ADD INDEX programs_is_active_index(is_active)",
    "ALTER TABLE programs ADD INDEX programs_is_active_sort_order_index(is_active, sort_order)",
    // sliders
    "ALTER TABLE sliders ADD INDEX sliders_is_active_sort_order_index(is_active, sort_order)",
    // partners
    "ALTER TABLE partners ADD INDEX partners_is_active_index(is_active)",
    // payment_confirmations
    "ALTER TABLE payment_confirmations ADD INDEX payment_confirmations_status_index(status)",
];

foreach ($indexes as $sql) {
    try {
        DB::statement($sql);
        echo "OK: " . substr($sql, 0, 80) . "...\n";
    } catch (\Exception $e) {
        echo "SKIP: " . $e->getMessage() . "\n";
    }
}

$exists = DB::table('migrations')->where('migration', '2026_07_12_204751_add_performance_indexes')->exists();
if (!$exists) {
    DB::table('migrations')->insert([
        'migration' => '2026_07_12_204751_add_performance_indexes',
        'batch' => $nextBatch,
    ]);
    echo "Marked performance_indexes as run (batch $nextBatch)\n";
}

echo "\nDone.\n";
