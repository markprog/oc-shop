<?php
$zip = new ZipArchive();
$zip->open('C:/Users/98831/Downloads/opencart-4.1.0.3.zip');

$files = array(
    // Cart logic
    'system/library/cart/cart.php',
    'system/library/cart/tax.php',
    'system/library/cart/customer.php',
    // Checkout
    'catalog/controller/checkout/checkout.php',
    'catalog/controller/checkout/cart.php',
    // Product model - key queries
    'catalog/model/catalog/product.php',
    // Category model
    'catalog/model/catalog/category.php',
    // Order model
    'catalog/model/checkout/order.php',
    // SEO URL startup
    'catalog/controller/startup/seo_url.php',
    // Admin product controller (structure)
    'admin/controller/catalog/product.php',
    // Config files
    'system/config/catalog.php',
    'system/config/admin.php',
    // Subscription plan
    'catalog/model/catalog/subscription_plan.php',
    // Affiliate model
    'admin/model/marketing/affiliate.php',
    // Coupon
    'catalog/model/marketing/coupon.php',
    // Returns
    'catalog/model/account/returns.php',
    // Review
    'catalog/model/catalog/review.php',
    // Wishlist
    'catalog/model/account/wishlist.php',
    // Language default catalog
    'catalog/language/en-gb/default.php',
    // Admin user group (permissions model)
    'admin/model/user/user_group.php',
    // SEO URL model
    'catalog/model/design/seo_url.php',
    // Banner model
    'catalog/model/design/banner.php',
    // Layout model
    'catalog/model/design/layout.php',
    // CMS article/topic
    'catalog/model/cms/article.php',
    'catalog/model/cms/topic.php',
    // Fraud extension hint
    'admin/controller/extension/fraud.php',
);

foreach ($files as $f) {
    $content = $zip->getFromName('upload/'.$f);
    echo "\n\n========== FILE: upload/$f ==========\n";
    if ($content === false) {
        echo "[NOT FOUND]\n";
    } else {
        // Limit per file to 200 lines to keep output manageable
        $lines = explode("\n", $content);
        echo implode("\n", array_slice($lines, 0, 200));
        if (count($lines) > 200) echo "\n... [".( count($lines)-200)." more lines truncated]";
    }
}
$zip->close();
