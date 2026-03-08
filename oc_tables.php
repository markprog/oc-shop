<?php
// Extract all table names from install SQL
// The SQL only contains INSERT INTO, so we need the schema from elsewhere
// Let's get ALL unique table names from INSERT statements
$zip = new ZipArchive();
$zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');
$sql = $zip->getFromName('upload/install/opencart-en-gb.sql');

// Extract all table names from INSERT INTO
preg_match_all('/INSERT INTO `(oc_[^`]+)`/i', $sql, $m);
$tables = array_unique($m[1]);
sort($tables);
foreach ($tables as $t) echo $t."\n";

echo "\n=== TOTAL: ".count($tables)." tables ===\n";
$zip->close();
