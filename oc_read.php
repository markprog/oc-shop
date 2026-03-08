<?php
// Read specific files from the zip for architecture analysis
$zip = new ZipArchive();
$zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');

$files_to_read = array(
    'upload/index.php',
    'upload/config-dist.php',
    'upload/admin/index.php',
    'upload/system/startup.php',
    'upload/system/framework.php',
    'upload/system/engine/action.php',
    'upload/system/engine/controller.php',
    'upload/system/engine/front.php',
    'upload/system/engine/loader.php',
    'upload/system/engine/model.php',
    'upload/system/engine/registry.php',
    'upload/system/engine/router.php',
    'upload/system/library/db.php',
    'upload/system/library/cache.php',
    'upload/system/library/session.php',
    'upload/system/library/event.php',
    'upload/system/library/template.php',
    'upload/system/library/language.php',
    'upload/system/library/config.php',
    'upload/system/library/request.php',
    'upload/system/library/response.php',
    'upload/system/library/url.php',
    'upload/system/library/user.php',
    'upload/system/library/cart.php',
    'upload/composer.json',
);

$arg = isset($argv[1]) ? $argv[1] : '';
if ($arg && in_array('upload/' . $arg, $files_to_read)) {
    $content = $zip->getFromName('upload/' . $arg);
    echo $content;
} else {
    foreach ($files_to_read as $f) {
        $content = $zip->getFromName($f);
        if ($content !== false) {
            echo "\n\n=== FILE: $f ===\n";
            echo $content;
        } else {
            echo "\n\n=== FILE: $f === [NOT FOUND]\n";
        }
    }
}
$zip->close();
