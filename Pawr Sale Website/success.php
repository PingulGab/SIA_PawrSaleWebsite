<?php 
$cart_count = 0;
session_start();
session_destroy();
include 'header.php';

require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "stripesecretkeyhere";
\Stripe\Stripe::setApiKey($stripe_secret_key);

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    $name = trim($_GET['name']);
    $email = $_GET['email'];
    $gender = $_GET['gender'];
    $street_building_num = $_GET['street_building'];
    $phone_num = $_GET['phone_num'];
    $city = $_GET['city'];
    $postal_code = $_GET['postal_code'];

    $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
    $current_datetime = $date->format('F j, Y (g:ia)');

    // Process name to get last name
    $name_parts = explode(' ', $name);
    $last_name = end($name_parts);

    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $customer = \Stripe\Customer::retrieve($session->customer);
        
        // Retrieve the line items for the session
        $line_items = \Stripe\Checkout\Session::allLineItems($session_id, ['limit' => 100]);

        $items = [];
        $total_price = 0;
        $htmlItems = ''; // Initialize empty variable to store HTML for items

        foreach ($line_items->data as $item) {
            $product = \Stripe\Product::retrieve($item->price->product);
            $unit_amount = $item->price->unit_amount / 100; // Convert to dollars
            $quantity = $item->quantity;
            $item_total = $unit_amount * $quantity;

            $items[] = [
                'name' => $product->name,
                'unit_price' => '₱'.number_format($unit_amount, 2),
                'quantity' => $quantity,
                'total' => '₱'.number_format($item_total, 2),
                'description' => $product->description
            ];

            // Construct HTML for current item
            $htmlItems .= '<tbody> <tr style="padding-bottom: 5px;"> <td class="product-cell"> <img src="'.$product->images[0].'" alt="'.$product->name.'"> <span style="word-wrap: normal; white-space: pre-wrap; vertical-align: top; text-align: left">'.$product->name.'</span> </td><td style="text-align: right;">₱'.number_format($unit_amount, 2).'</td><td style="text-align: right;">'.$quantity.'</td><td style="text-align: right;">₱'.number_format($item_total, 2).'</td></tr></tbody>';

            $total_price += $item_total;
        }

        // Prepare the email data
        $emailData = [
            'email' => $email,
            'items' => $items,
            'total_price' => '₱'.number_format($total_price, 2),
            'name' => $name,
            'last_name' => $last_name, // Add last name to the email data
            'gender' => $gender,
            'street_and_building' => $street_building_num,
            'phone_number' => $phone_num,
            'city' => $city,
            'postal_code' => $postal_code,
            'html_items' => $htmlItems,
            'datetime' => $current_datetime
        ];

        // Integrately webhook URL
        $integratelyWebhook = 'integration_webhook_here';

        // Send the email data to Integrately
        $ch = curl_init($integratelyWebhook);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
        $response = curl_exec($ch);
        curl_close($ch);

    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successful Payment - Pawr Sale</title>
</head>
<body>
    <div class="container">
        <div class="masthead">
            <div class="paymentSuccessfulContainer">
                <div class="row" style="margin: 80px; margin-left: 120px; margin-right: 120px;">
                    <div class="col-lg-6">
                        <img src="assets/img/Success_Mascot.png" style="max-height: 500px;" alt="Successful Payment Mascot">
                    </div>

                    <div class="col-lg-6">
                        <div class="paymentSuccessful_Message">
                            <h1> Payment Succesful! </h1>
                            <p style="text-align: justify;"> We are delighted to inform you that your payment has been successfully processed. </p>
                            <p> Thank you for choosing our products! </p>
                            <p style="text-align: justify"> An automated receipt has been generated and sent to the email address that you have provided.
                            Your order is now confirmed and will be processed shortly. </p>
                            <p style="margin-top: 50px; margin-bottom: 0px;"> For futher inquiries contact us at <a href="mailto:sia.pawrsale@gmail.com"> sia.pawrsale@gmail.com </a> </p>
                        </div>

                        <div class="paymentSuccessful_SocialMediaContainer">
                            <div class="paymentSuccessful_SocialMediaIconContainer">
                                <a href="#"> <img src="assets/img/Icon_Facebook.png" class="img_icons_XL"> </a>
                                <a href="#"> <img src="assets/img/Icon_Instagram.png" class="img_icons_XL"> </a>
                                <a href="#"> <img src="assets/img/Icon_X.png" class="img_icons_XL"> </a>
                                <a href="#"> <img src="assets/img/Icon_Tiktok.png" class="img_icons_XL"> </a>
                            </div>
                        </div>

                        <div class="paymentSuccessful_ButtonContainer">
                            <button class="paymentSuccessful_BackButton"> <a href="index.php"> Confirm </a> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
