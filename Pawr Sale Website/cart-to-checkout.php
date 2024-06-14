<?php
include 'header.php';

// Check if cart exists in session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_empty = empty($cart_items);

$cache_dir = __DIR__ . '/cache';
$cache_file = $cache_dir . '/cart_cache.json';

if (file_exists($cache_file)) {
    $cart_items = json_decode(file_get_contents($cache_file), true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Pawr Sale</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script>
    <script>
        function updateTotalPrice() {
            let total = 0;
            document.querySelectorAll('[id^="subtotal-"]').forEach(subtotalElement => {
                total += parseFloat(subtotalElement.textContent.replace(/[^0-9.-]+/g, ""));
            });
            document.getElementById('ctoc_totalPrice').textContent = "₱" + numberWithCommas(total.toFixed(2));
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateTotalPrice();
            console.log("updateTotalPrice initiated.");
        });
    </script>
</head>
<body>
    <div class="masthead">
        <div class="container" style="max-width:1500px;">
            <div class="row">
                <div class="col-lg-7">
                    <h2 class="ctoc_header">My Cart</h2>
                    <div class="ctoc_cart">
                        <div class="row">
                            <div class="col-lg-5"><p class="ctoc_cart_header">Product</p></div>
                            <div class="col-lg-2"><p class="ctoc_cart_header">Quantity</p></div>
                            <div class="col-lg-2"><p class="ctoc_cart_header">Price</p></div>
                            <div class="col-lg-2"><p class="ctoc_cart_header">Subtotal</p></div>
                        </div>
                        <hr style="padding:0px; margin-top:0px;">
                        <div class="ctoc_cart_productContainer">
                            <?php if ($cart_empty): ?>
                                <div class="ctoc_empty">
                                    <p>Your cart is empty</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($cart_items as $product_id => $item): ?>
                                    <?php
                                    $name = $item['name'] ?? 'Unknown';
                                    $quantity = $item['quantity'] ?? 0;
                                    $price = $item['price'] ?? 0.00;
                                    $subtotal = $price * $quantity;
                                    ?>
                                    <div class="row" id="product-<?php echo $product_id; ?>">
                                        <div class="col-lg-5" style="display: flex; align-items: center;">
                                            <img src='<?php echo htmlspecialchars($item['images'][0])?>' style='height: 100px; width: 100px; border-radius:10px; object-fit: cover;'>
                                            <p class="ctoc_productName"><?php echo htmlspecialchars($name); ?></p>
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="cart_btn" onclick='ctoc_updateQuantity("<?php echo $product_id; ?>", -1)'> - </button>
                                            <input type="number" class="cart_inputbox" id="quantity-<?php echo $product_id; ?>" value="<?php echo $quantity; ?>" disabled maxlength="2" oninput='this.value=this.value.slice(0,2)'>
                                            <button type="button" class="cart_btn" onclick='ctoc_updateQuantity("<?php echo $product_id; ?>", 1)'> + </button>
                                            <button type="button" class="cart_btn cart_edit_btn" id="edit-<?php echo $product_id; ?>" onclick='ctoc_toggleEdit("<?php echo $product_id; ?>")'>
                                                <img src="assets/img/Icon_Checkout_Edit.png" class="img_icons_S" alt="" data-hidden-input="Edit">
                                            </button>
                                        </div>
                                        <div class="col-lg-2">
                                            <p>₱<?php echo number_format($price, 2); ?></p>
                                        </div>
                                        <div class="col-lg-2">
                                            <p>₱<span id="subtotal-<?php echo $product_id; ?>" data-unit-price="<?php echo $price; ?>"><?php echo number_format($subtotal, 2); ?></span></p>
                                        </div>
                                        <div class="col-lg-1" style="display:flex; justify-content: flex-end;">
                                            <button type="button" class="cart_btn_remove" onclick='ctoc_removeProduct("<?php echo $product_id; ?>")' id='ctoc_removeBtn-<?php echo "$product_id"; ?>'>
                                                <img src="assets/img/Icon_Checkout_Remove.png" class="img_icons_S" id='removeIMG-<?php echo "$product_id"; ?>'alt="" onmouseover='changeRemoveImage(<?php echo "\"$product_id\""; ?>)' onmouseout='restoreRemoveImage(<?php echo "\"$product_id\""; ?>)'>
                                            </button>
                                        </div>
                                        <hr style="margin-top: 10px; margin-bottom: 10px;">
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="ctoc_totalPriceContainer">
                            <span >
                                <span class="ctoc_totalPriceSpan" style="font-weight: bold; margin-right: 10px;">Total</span>
                                <span class="ctoc_totalPriceSpan" id='ctoc_totalPrice'></span>
                            </span>
                        </div>
                    </div>

                    <div class="ctoc_continueShopping">
                        <img src="assets/img/Icon_Checkout_Back.png" class="img_icons_L">
                        <a href="products.php"> Continue Shopping </a>
                    </div>
                </div>
                <!---Checkout Information--->
                <div class="col-lg-5">
                    <h2 class="ctoc_header"> Shipping Information </h2>
                    <div class="ctoc_info">
                        <div class="container">
                            <form id="checkout-form" method="POST" action="checkout.php">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="name">Name </label>
                                        <input type="text" style="width: 100%; border-radius:10px; border:solid 1px; padding-left:15px;" id="name" name="name" value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="gender" style="margin-right: 5px;">Gender</label>
                                        <br>
                                        <select id="gender" name="gender" required style="width: 100%; border-radius:10px; border:solid 1px; padding-left:15px; height: 28px;">
                                            <option value="male" <?php if(isset($_SESSION['gender']) && $_SESSION['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                            <option value="female" <?php if(isset($_SESSION['gender']) && $_SESSION['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                            <option value="other" <?php if(isset($_SESSION['gender']) && $_SESSION['gender'] == 'other') echo 'selected'; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-lg-6">    
                                        <label for="email">Email</label>
                                        <input type="email" style="width: 100%; border-radius:10px; border:solid 1px; padding-left:15px;" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="phone_num">Phone Number</label>
                                        <input type="tel" style="width: 100%; border-radius:10px; border:solid 1px; padding-left:15px;" id="phone_num" name="phone_num" pattern="^\+63\d{10}$" placeholder="+63XXXXXXXXXX" value="<?php echo isset($_SESSION['phone_num']) ? htmlspecialchars($_SESSION['phone_num']) : ''; ?>" required>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="street_building_num">Street & Building No.</label>
                                        <input type="text" style="width: 100%; border-radius:10px; border:solid 1px; padding-left:15px;" id="street_building_num" name="street_building_num" value="<?php echo isset($_SESSION['street_building_num']) ? htmlspecialchars($_SESSION['street_building_num']) : ''; ?>" required>
                                    </div>
                                </div>

                                <div class="row" style="padding-top: 10px;">
                                    <div class="col-lg-6">
                                        <label for="city">City</label>
                                        <input type="text" style="width: 100%; border-radius:10px; border:solid 1px; padding-left:15px;" id="city" name="city" value="<?php echo isset($_SESSION['city']) ? htmlspecialchars($_SESSION['city']) : ''; ?>" required>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="postal_code">Postal Code</label>
                                        <input type="number" style="width: 100%; border-radius:10px; border:solid 1px; padding-left:15px;" id="postal_code" name="postal_code" value="<?php echo isset($_SESSION['postal_code']) ? htmlspecialchars($_SESSION['postal_code']) : ''; ?>" required>
                                    </div>
                                </div>

                                <div class="row" style="display:flex; align-items: center; margin-left: auto; margin-right: auto; margin-top: 50px; padding: 20px;">
                                    <div class="col-lg-4">
                                        <img src="assets/img/CtoC_ThankYou.png" alt="Thank You" style="max-width: 150px">
                                    </div>
                                    
                                    <div class="col-lg-8">
                                        <div style="">
                                            <h4 style=" margin: 0px; padding: 0px;"> You are one step away </h4>
                                            <h5 style="margin: 0px; padding: 0px;"> from giving your pet the best gift! </h5>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                    <div style="display:flex; align-content:center; justify-content:center; padding-top: 50px;">
                        <?php if (!$cart_empty): ?>
                            <button class="ctoc_checkout_btn" type="submit">Checkout</button>
                        <?php else: ?>
                            <button class="ctoc_checkout_btn" type="submit" disabled>Checkout</button>
                        <?php endif; ?>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
