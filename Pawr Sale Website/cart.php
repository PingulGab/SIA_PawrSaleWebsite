<?php
session_start();
require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "stripesecretkeyhere";
\Stripe\Stripe::setApiKey($stripe_secret_key);

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
$products = [];

if (empty($cart)) {
    // Cart is empty, display "No product added" message
    echo json_encode([
        'cartItemsHtml' => "
        
        <div style='position: absolute; top: 40%; left: 25%; display: flex; align-items:center; flex-direction: column;'>
            <img src='assets/img/Notification_NoProductAddedDog.png' style='width: 200px;'>
            <p> Cart is empty. </p>
        </div>
        
        ",
        'total' => number_format($total, 2)
    ]);
    exit;
}

foreach ($cart as $product_id => $item) {
    $quantity = $item['quantity'];

    try {
        $product = \Stripe\Product::retrieve($item['product_id']);
        $price = \Stripe\Price::retrieve($item['price_id']);

        $products[$product_id] = [
            "name" => $product->name,
            "price" => $price->unit_amount / 100, // Convert cents to dollars
            "quantity" => $quantity,
            "images" => $product->images,
            "subtotal" => ($price->unit_amount / 100) * $quantity // Calculate subtotal
        ];

        $total += $price->unit_amount / 100 * $quantity;
    } catch (\Exception $e) {
        echo "Error retrieving product details: " . $e->getMessage();
    }
}

// If the request is an AJAX request, return the cart items as HTML
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    ob_start();
    foreach ($products as $product_id => $product) {
        echo "<div class='row' style='margin-left: 0; margin-top: 10px; margin-bottom: 10px;' id='product-$product_id'>";
        echo "<hr>";
        //IMAGE
        foreach ($product['images'] as $image) {
            ?>
            <div class='col-lg-3'>
                <img src='<?php echo $image ?>' class='product-image' style='height: 100px; width: 100px; border-radius:10px;'>
            </div>
            <?php
        }
        ?>

        <div class='col-lg-9 cart_product_individual' style="position: relative;">
            <div class="row">
                <!--- Name --->
        <div class="col-lg-7" style="font-size: 18px; font-weight: bold;">
            <?php echo htmlspecialchars($product["name"]); ?>
        </div>
        <!--- Price --->
        <div class="col-lg-5" style="display: flex; justify-content: flex-end;">
            <p> ₱<?php echo number_format($product["price"], 2); ?>
            </p>
        </div>
    </div>

    <div class="row">
        <!--- Minus Button --->
        <div class="col-lg-12">

            <div class="row">
                <div class="col-lg-5">
                    <button type="button" class="cart_btn"
                        onclick='updateQuantity(<?php echo "\"$product_id\", -1"; ?>)'> - </button>

                    <!--- Input Box --->
                    <input type="number" class="cart_inputbox" id='quantity-<?php echo "$product_id"; ?>'
                        value='<?php echo "{$product['quantity']}" ?>' disabled maxlength='2'
                        oninput='this.value=this.value.slice(0,2)'>

                    <!--- Add Button --->
                    <button type="button" class="cart_btn" onclick='updateQuantity(<?php echo "\"$product_id\", 1"; ?>)'>
                        + </button>

                    <!--- Edit and/or Done Button --->
                    <button type="button" class="cart_btn cart_edit_btn" id='edit-<?php echo "$product_id"; ?>'
                        onclick='toggleEdit(<?php echo "\"$product_id\""; ?>)'> 
                        <img src="assets/img/Icon_Checkout_Edit.png" class="img_icons_S" alt="" data-hidden-input="Edit">
                    </button>
                </div>

                <!--- Subtotal --->
                <div class="col-lg-7" style="display: flex; justify-content: flex-end;">
                    <span >
                        <span style='color: #989898; font-weight: bold;'>Subtotal</span> ₱<span id='subtotal-<?php echo "$product_id"; ?>'
                        data-unit-price='<?php echo "{$product['price']}"; ?>'><?php echo number_format($product['subtotal'], 2); ?></span>
                    </span>
                </div>
            </div>

            <div class="row"> <p style="color: rgb(0,0,0,0);"> x </p> </div>

            <div class="row" style="position: absolute; right: 15px;">
                <div  style="display: flex; justify-content: flex-end;">
                    <!--- Remove Button --->
                        <button type="button" onmouseover='changeRemoveImage(<?php echo "\"$product_id\""; ?>)' onmouseout='restoreRemoveImage(<?php echo "\"$product_id\""; ?>)' id='cart_removeButton-<?php echo "$product_id"; ?>' class="cart_btn_remove" onclick='removeProduct(<?php echo "\"$product_id\""; ?>)'>
                            <img src="assets/img/Icon_Checkout_Remove.png" class="img_icons_S" alt="" id="removeIMG-<?php echo "$product_id"; ?>">
                        </button>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
</div>
<?php
                echo "</div>";
    }
    $cartItemsHtml = ob_get_clean();
    echo json_encode([
        'cartItemsHtml' => $cartItemsHtml,
        'total' => number_format($total, 2)
    ]);
    exit;
}
?>