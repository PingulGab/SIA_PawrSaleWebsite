<?php
session_start();
require __DIR__ . "/vendor/autoload.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);


$stripe_secret_key = "stripesecretkeyhere";
\Stripe\Stripe::setApiKey($stripe_secret_key);

// Ensure the cache directory exists
$cache_dir = __DIR__ . '/cache';
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

// Prepare cart items with details for JSON cache
$cart_items_with_details = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cart_item) {
        $product_id = $cart_item['product_id'];
        $product_details = $_SESSION['product_details'][$product_id];
        $cart_items_with_details[] = array_merge($cart_item, $product_details);
    }
}

// Update JSON cache file
$cache_file = $cache_dir . '/cart_cache.json';
file_put_contents($cache_file, json_encode($cart_items_with_details, JSON_PRETTY_PRINT));

// Return success response
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;
?>