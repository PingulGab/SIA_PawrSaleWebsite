<?php
require __DIR__ . "/vendor/autoload.php";

// Get product data from Stripe using Stripe's PHP library
$stripe_secret_key = "stripesecretkeyhere";
\Stripe\Stripe::setApiKey($stripe_secret_key);

// Retrieve product details based on the product ID received from AJAX request
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $price_id = $_GET['price_id'];
    $product_retrieved = \Stripe\Product::retrieve($product_id);
    $price_retrieved = \Stripe\Price::retrieve($price_id);
    
    $productImages = $product_retrieved->images;
    $productName = $product_retrieved->name;
    $productDescription = $product_retrieved->description;
    
    $unit_amount = $price_retrieved->unit_amount;
    $currency = strtoupper($price_retrieved->currency);
    
    switch($currency){
        case "USD":
            $formatted_currency = "$";
            break;
        case "PHP":
            $formatted_currency = "₱";
            break;
        default:
            $formatted_currency = "";
            break;
    }
    
    $total_price = $formatted_currency . number_format($unit_amount / 100, 2);
    $unit_price = $unit_amount / 100;
} else {
    $product_id = null;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php if ($product_id): ?>
    <div class="row" style="position: relative">
        <div class="col-lg-5" style="display: flex;">
            <?php foreach ($productImages as $image): ?>
                <!--- Image --->
                <img src="<?php echo htmlspecialchars($image); ?>" class="product_detailsImage">
            <?php endforeach; ?>
        </div>

        <div class="col-lg-7 product_detailsTxt">
            <!--- Name --->
            <h2 class="product_detailsName"><?php echo htmlspecialchars($productName); ?></h2>

            <!--- Description --->
            <div style="width: 80%">
                <p> <?php echo htmlspecialchars($productDescription); ?></p>
            </div>

            <div class="product_detailsTotal_AddToCart">
                <div style="display: flex; justify-content: center;">
                    <!--- Quantity Updater --->
                    <form id="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="price_id" value="<?php echo $price_id; ?>">
                        <button type="button" onclick="decrementQuantity('quantity_prd_details-<?php echo $product_id; ?>')">-</button>
                        <input type="number" id="quantity_prd_details-<?php echo $product_id; ?>" name="quantity" value="1" min="1" max="99" data-price="<?php echo ($unit_amount / 100); ?>" oninput="updateTotalPrice('quantity_prd_details-<?php echo $product_id; ?>')" onchange="checkMinMax(this)">
                        <button type="button" onclick="incrementQuantity('quantity_prd_details-<?php echo $product_id; ?>')">+</button>
                        
                        <!--- Add to Cart Button --->
                        <button class="product_details_AddToCartBTN" type="button" id="prd_addtocart" onclick="addToCart('<?php echo $product_id; ?>', '<?php echo $price_id; ?>', document.getElementById('quantity_prd_details-<?php echo $product_id; ?>').value);">
                            <img src="assets/img/Icon_Cart.png" class="img_icons_M"> Add to Cart
                        </button>
                    </form>
                </div>

                <!--- Total --->
                <div style="display: flex; flex-direction: row; margin-top: 10px;">
                    <p style="font-weight: bold;">Total:&nbsp;</p> <p id="total-price"><?php echo $total_price; ?></p>
                    <input type="hidden" id="unit-price" value="<?php echo htmlspecialchars($unit_price); ?>">
                </div>
                
            </div>
            <?php else: ?>
                <p>Product ID not provided.</p>
            <?php endif; ?>
        </div>
    </div>
    <div>
    </div>
    <script src="js/scripts.js"></script>
</body>
</html>
