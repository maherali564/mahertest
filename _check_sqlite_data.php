<?php
$db = new PDO('sqlite:database/database.sqlite');

echo "=== Site Settings ===\n";
$rs = $db->query("SELECT * FROM site_settings");
foreach($rs as $r) { echo json_encode($r, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . "\n"; }

echo "\n=== Sliders ===\n";
$rs = $db->query("SELECT * FROM sliders");
foreach($rs as $r) { echo "id={$r['id']} title=" . json_encode($r) . "\n"; }

echo "\n=== Campaigns ===\n";
$rs = $db->query("SELECT id, title_ar, title_en FROM campaigns");
foreach($rs as $r) { echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n"; }

echo "\n=== Projects ===\n";
$rs = $db->query("SELECT id, title_ar, title_en FROM projects");
foreach($rs as $r) { echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n"; }

echo "\n=== Stories ===\n";
$rs = $db->query("SELECT id, title_ar, title_en FROM stories");
foreach($rs as $r) { echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n"; }

echo "\n=== Partners ===\n";
$rs = $db->query("SELECT * FROM partners");
echo $rs->rowCount() . " rows\n";

echo "\n=== Programs ===\n";
$rs = $db->query("SELECT id, title_ar, title_en FROM programs");
foreach($rs as $r) { echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n"; }

echo "\n=== Statistics ===\n";
$rs = $db->query("SELECT id, label_ar, label_en, value FROM statistics");
foreach($rs as $r) { echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n"; }
