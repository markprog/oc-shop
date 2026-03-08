<?php
$zip = new ZipArchive();
$zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');

$all = array();
for ($i = 0; $i < $zip->numFiles; $i++) {
    $all[] = $zip->getNameIndex($i);
}

$arg = isset($argv[1]) ? $argv[1] : 'list';

if ($arg === 'catalog_controllers') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/catalog/controller/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'admin_controllers') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/admin/controller/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'catalog_models') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/catalog/model/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'admin_models') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/admin/model/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'catalog_views') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/catalog/view/') === 0 && substr($f,-5)==='.twig') echo $f."\n";
    }
} elseif ($arg === 'admin_views') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/admin/view/') === 0 && substr($f,-5)==='.twig') echo $f."\n";
    }
} elseif ($arg === 'extension_controllers') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/extension/') === 0 && strpos($f,'/controller/')!==false && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'system_library') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/system/library/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'system_engine') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/system/engine/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'config_files') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/system/config/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'install_files') {
    foreach ($all as $f) {
        if (strpos($f, 'upload/install/') === 0 && substr($f,-4)==='.php') echo $f."\n";
    }
} elseif ($arg === 'extension_list') {
    $exts = array();
    foreach ($all as $f) {
        if (preg_match('#^upload/extension/opencart/(admin|catalog)/controller/extension/opencart/([^/]+)/([^/]+)\.php$#', $f, $m)) {
            $exts[$m[2]][$m[3]] = true;
        }
    }
    foreach ($exts as $type => $names) {
        foreach (array_keys($names) as $n) echo "$type/$n\n";
    }
} elseif ($arg === 'read') {
    $file = isset($argv[2]) ? $argv[2] : '';
    $content = $zip->getFromName('upload/'.$file);
    echo $content !== false ? $content : "[NOT FOUND: upload/$file]";
} elseif ($arg === 'lang_admin_default') {
    $content = $zip->getFromName('upload/admin/language/en-gb/default.php');
    echo $content;
} elseif ($arg === 'lang_catalog_default') {
    $content = $zip->getFromName('upload/catalog/language/en-gb/default.php');
    echo $content;
} elseif ($arg === 'install_sql') {
    foreach ($all as $f) {
        if (strpos($f,'install/') !== false && (substr($f,-4)==='.sql' || substr($f,-7)==='.sql.gz')) echo $f."\n";
    }
    // also check for opencart.sql in root
    foreach ($all as $f) {
        if (substr($f,-4)==='.sql') echo $f."\n";
    }
} elseif ($arg === 'db_schema') {
    foreach ($all as $f) {
        if (substr($f,-4)==='.sql') {
            echo "=== $f ===\n";
            echo $zip->getFromName($f)."\n";
        }
    }
}

$zip->close();
