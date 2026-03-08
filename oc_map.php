<?php
$zip = new ZipArchive();
$zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');
$names = array();
for ($i = 0; $i < $zip->numFiles; $i++) {
    $names[] = $zip->getNameIndex($i);
}

// Full directory tree up to 5 levels inside upload/
$dirs = array();
foreach ($names as $n) {
    if (strpos($n, 'upload/') !== 0) continue;
    $parts = explode('/', $n);
    for ($d = 2; $d <= min(count($parts), 6); $d++) {
        $key = implode('/', array_slice($parts, 0, $d));
        $dirs[$key] = true;
    }
}
ksort($dirs);
foreach (array_keys($dirs) as $d) echo $d . PHP_EOL;
$zip->close();
