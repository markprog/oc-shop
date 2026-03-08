<?php
$zip = new ZipArchive();
$zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');

$files = array(
    'admin/model/catalog/product.php',
    'catalog/model/account/wishlist.php',
    'catalog/model/account/returns.php',
    'catalog/model/account/reward.php',
    'catalog/model/account/transaction.php',
    'catalog/model/account/affiliate.php',
    'catalog/model/account/subscription.php',
    'admin/model/marketing/affiliate.php',
    'admin/model/marketing/coupon.php',
    'admin/model/sale/order.php',
    'admin/model/sale/returns.php',
    'admin/model/sale/subscription.php',
    'admin/model/user/user_group.php',
    'catalog/model/design/banner.php',
    'catalog/model/design/layout.php',
    'catalog/model/cms/article.php',
    'system/config/catalog.php',
    'system/config/admin.php',
    'catalog/language/en-gb/default.php',
    'admin/language/en-gb/default.php',
    'admin/controller/setting/setting.php',
    'catalog/controller/product/product.php',
    'catalog/controller/product/category.php',
    'catalog/controller/product/search.php',
    'catalog/controller/account/account.php',
    'catalog/controller/account/affiliate.php',
    'catalog/controller/information/contact.php',
    'admin/controller/report/statistics.php',
    'catalog/controller/cron/subscription.php',
    'admin/controller/marketing/coupon.php',
);

foreach ($files as $f) {
    $content = $zip->getFromName('upload/'.$f);
    echo "\n========== FILE: $f ==========\n";
    if ($content === false) {
        echo "[NOT FOUND]\n";
    } else {
        $lines = explode("\n", $content);
        echo implode("\n", array_slice($lines, 0, 120));
        if (count($lines) > 120) echo "\n... [truncated]\n";
    }
}
$zip->close();
