<?php
session_start();
require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "stripesecretkeyhere";
\Stripe\Stripe::setApiKey($stripe_secret_key);

ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

header('Content-Type: application/json');

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action !== 'remove' && !isset($_SESSION['cart'][$product_id])) {
        echo json_encode(['error' => 'Product not in cart']);
        return;
    }

    $quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id]['quantity'] : 0;
    $price_id = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id]['price_id'] : '';

    switch ($action) {
        case 'increment':
            $quantity++;
            break;
        case 'decrement':
            $quantity = max(1, $quantity - 1);
            break;
        case 'update':
            $new_quantity = intval($_POST['quantity']);
            if ($new_quantity > 0 && $new_quantity <= 99) {
                $quantity = $new_quantity;
            }
            break;
        case 'remove':
            unset($_SESSION['cart'][$product_id]);
            $quantity = 0;
            break;
    }

    if ($quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }

    $total = 0;
    foreach ($_SESSION['cart'] as $id => $item) {
        $price = \Stripe\Price::retrieve($item['price_id']);
        $total += $price->unit_amount / 100 * $item['quantity'];
    }

    $cart_count = count($_SESSION['cart']);

    echo json_encode([
        'newQuantity' => $quantity,
        'newTotal' => number_format($total, 2),
        'cart_count' => $cart_count
    ]);
    exit;
}
?>
