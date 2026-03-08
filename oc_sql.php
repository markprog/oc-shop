<?php
$zip = new ZipArchive();
$zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');
$sql = $zip->getFromName('upload/install/opencart-en-gb.sql');
echo $sql;
$zip->close();
