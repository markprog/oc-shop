<?php
$zip = new ZipArchive();
$result = $zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');
if ($result !== TRUE) { echo "ZIP ERROR: $result"; exit; }
$names = array();
for ($i = 0; $i < $zip->numFiles; $i++) {
    $n = $zip->getNameIndex($i);
    if (strpos($n, '.sql') !== false) $names[] = $n;
}
foreach ($names as $n) echo $n."\n";
echo "---\n";
$sql = $zip->getFromName('upload/install/opencart-en-gb.sql');
if ($sql === false) {
    echo "NOT FOUND\n";
} else {
    // Print just the CREATE TABLE statements (table names)
    preg_match_all('/CREATE TABLE `([^`]+)`/i', $sql, $m);
    foreach ($m[1] as $t) echo "TABLE: $t\n";
    echo "\n=== FULL SCHEMA (first 8000 chars) ===\n";
    echo substr($sql, 0, 8000);
}
$zip->close();
