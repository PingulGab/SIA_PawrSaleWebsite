<?php
// Start output buffering
ob_start();

require __DIR__ . "/vendor/autoload.php";

// Stripe setup
$stripe_secret_key = "stripesecretkeyhere";
\Stripe\Stripe::setApiKey($stripe_secret_key);

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle POST request to store customer details in the session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['phone_num'] = $_POST['phone_num'];
    $_SESSION['street_building_num'] = $_POST['street_building_num'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['postal_code'] = $_POST['postal_code'];
}

// Retrieve customer details from the session
$name = $_SESSION['name'] ?? '';
$email = $_SESSION['email'] ?? '';
$gender = $_SESSION['gender'] ?? '';
$phone_num = $_SESSION['phone_num'] ?? '';
$street_building_num = $_SESSION['street_building_num'] ?? '';
$city = $_SESSION['city'] ?? '';
$postal_code = $_SESSION['postal_code'] ?? '';

$cart = $_SESSION['cart'] ?? [];
$line_items = [];

foreach ($cart as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price_id = $item['price_id'];

    try {
        $product = \Stripe\Product::retrieve($product_id);
        $price = \Stripe\Price::retrieve($price_id);

        $line_item = [
            'price' => $price_id,
            'quantity' => $quantity,
        ];
        $line_items[] = $line_item;
    } catch (\Exception $e) {
        echo "Error retrieving product details: " . $e->getMessage();
        exit;
    }
}

try {
    $customer = \Stripe\Customer::create([
        'name' => $name,
        'email' => $email,
        'phone' => $phone_num,
        'shipping' => [
            'name' => $name,
            'address' => [
                'line1' => $street_building_num,
                'city' => $city,
                'postal_code' => $postal_code
            ]
        ]
    ]);

    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'mode' => 'payment',
        'currency' => 'PHP',
        'customer' => $customer->id,
        'line_items' => $line_items,
        'success_url' => 'https://localhost/sia-latest/success.php?session_id={CHECKOUT_SESSION_ID}&name=' . urlencode($name) . '&email=' . urlencode($email) . '&gender=' . urlencode($gender) . '&street_building=' . urlencode($street_building_num) . '&phone_num=' . urlencode($phone_num) . '&city=' . urlencode($city) . '&postal_code=' . urlencode($postal_code),
        'cancel_url' => 'https://localhost/sia-latest/cancel.php',
    ]);

    // Redirect to the checkout session URL
    header("Location: " . $checkout_session->url);
    exit;
} catch (\Exception $e) {
    echo "Error creating checkout session: " . $e->getMessage();
    exit;
}

// End output buffering and flush output
ob_end_flush();

// Include the header after all header modifications
require 'header.php';
?>
