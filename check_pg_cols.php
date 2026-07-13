<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['posts', 'stories', 'donations', 'projects', 'emergency_campaigns', 'programs', 'sliders', 'partners',
           'contact_submissions', 'donation_submissions', 'volunteers', 'statistics', 'payment_confirmations'];
foreach ($tables as $t) {
    try {
        $cols = Schema::getColumnListing($t);
        echo "$t: " . implode(', ', $cols) . "\n";
    } catch (\Exception $e) {
        echo "$t: TABLE NOT FOUND\n";
    }
}
