<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['stories', 'projects', 'posts', 'emergency_campaigns'];
foreach ($tables as $table) {
    $cols = DB::select("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE FROM information_schema.COLUMNS WHERE TABLE_NAME = ? AND COLUMN_NAME IN ('person_name','location','images','videos')", [$table]);
    echo "$table:\n";
    foreach ($cols as $c) echo "  {$c->COLUMN_NAME}: {$c->DATA_TYPE} (nullable: {$c->IS_NULLABLE})\n";
    echo "\n";
}
