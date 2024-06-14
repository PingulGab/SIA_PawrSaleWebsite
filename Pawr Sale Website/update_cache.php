<?php
require __DIR__ . "/vendor/autoload.php";

use Stripe\Stripe;
use Stripe\Price;
use Stripe\Product;

$stripe_secret_key = "stripesecretkeyhere";
Stripe::setApiKey($stripe_secret_key);

$cache_dir = __DIR__ . '/cache';
$cache_file = $cache_dir . '/products_cache.json';

// Ensure the cache directory exists
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0777, true);
}

try {
    $prices = Price::all(["limit" => 100]);
    $products = [];
    foreach ($prices->data as $price_data) {
        $product = Product::retrieve($price_data->product);
        $products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'images' => $product->images,
            'category' => $product->metadata['category'] ?? 'Uncategorized',
            'price_id' => $price_data->id,
            'price' => $price_data->unit_amount,
            'currency' => $price_data->currency,
        ];
    }
    $data = ['products' => $products];
    file_put_contents($cache_file, json_encode($data));
} catch (Exception $e) {
    echo "Error updating cache: " . $e->getMessage();
}
?>
