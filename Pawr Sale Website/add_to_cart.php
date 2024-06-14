<?php
session_start();
require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "stripesecretkeyhere";
\Stripe\Stripe::setApiKey($stripe_secret_key);

$response = ['success' => false, 'cart_count' => 0];

if (isset($_POST['product_id']) && isset($_POST['quantity']) && isset($_POST['price_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    $price_id = $_POST['price_id'];

    if ($quantity > 0) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart
        $product_found = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['product_id'] == $product_id) {
                $cart_item['quantity'] += $quantity;
                $product_found = true;
                break;
            }
        }

        // If the product is not found in the cart, add it as a new entry
        if (!$product_found) {
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'price_id' => $price_id,
                'quantity' => $quantity
            ];
        }

        // Retrieve and cache product details if not already cached
        if (!isset($_SESSION['product_details'][$product_id])) {
            try {
                $product = \Stripe\Product::retrieve($product_id);
                $price = \Stripe\Price::retrieve($price_id);

                $_SESSION['product_details'][$product_id] = [
                    "name" => $product->name,
                    "price" => $price->unit_amount / 100, // Convert cents to dollars
                    "images" => $product->images
                ];
            } catch (\Exception $e) {
                echo "Error retrieving product details: " . $e->getMessage();
            }
        }

        // Ensure the cache directory exists
        $cache_dir = __DIR__ . '/cache';
        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, 0755, true);
        }

        // Update JSON cache file
        $cache_file = $cache_dir . '/cart_cache.json';
        file_put_contents($cache_file, json_encode($_SESSION['cart'], JSON_PRETTY_PRINT));

        $response['success'] = true;
    }

    $response['cart_count'] = count($_SESSION['cart']);
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
