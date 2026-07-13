<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=sahem', 'root', 'MaherAli1!');
$tables = ['stories','projects','donations','posts','emergency_campaigns','programs','sliders','partners','volunteers','contact_submissions','donation_submissions','statistics','payment_confirmations'];
foreach ($tables as $t) {
    $s = $pdo->query("SHOW COLUMNS FROM `$t`");
    echo "$t:\n";
    foreach ($s as $r) echo "  {$r['Field']} ({$r['Type']})\n";
    echo "\n";
}
