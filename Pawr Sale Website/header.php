<?php
session_start();
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$total = 0; // Initialize the total variable

// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="assets/PawrSale_IconLogo.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,600;1,600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,500;0,600;0,700;1,300;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,400;1,400&amp;display=swap"
        rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Concert One&display=swap">
    <style>
        /* Sidebar styling */
        .cart-sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1000;
            top: 0;
            right: 0;
            background-color: white;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
        }

        .cart-sidebar a {
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .cart-sidebar a:hover {
            color: #f6ba01;
        }

        .cart-sidebar .closebtn {
            right: 25px;
            font-size: 36px;
            margin-right: 10px;
        }

        /* Overlay styling */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 500;
            display: none;
        }

        .checkoutWrapper {
            position: fixed;
            bottom: 0;
            width: 410px;
            height: 60px;
            background-color: #464691;
            padding: 10px;
            margin-right: 15px;
            box-sizing: border-box;
            /* Include padding and border in the element's total width and height */
            z-index: 1;
            /* Ensure the wrapper is above the cart items */
        }

        .checkoutWrapper:hover{
            background-color: #282877;
        }

        .checkoutWrapper p {
            font-size: 16px;
            margin: 0;
        }

        .checkoutWrapper a {
            color: #f6ba01;
        }

        /* Active navbar link styling */
        .nav-link.active {
            color: #f6ba01 !important;
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function openCart() {
            // Show the sidebar instantly
            document.getElementById("cartSidebar").style.width = "425px";
            document.getElementById("overlay").style.display = "block";

             // Show loading animation while fetching data
             $('#cartItems').html("<div class='loading-container'><img src='assets/img/loading_goldenRetriever.gif' alt='running dog'></div>");
             $('#totalPrice').text('');

            // Perform the AJAX request to load cart items
            $.ajax({
                url: 'cart.php',
                type: 'POST',
                data: {
                    ajax: 1
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    $('#cartItems').html(data.cartItemsHtml);
                    $('#totalPrice').text('Total: ₱' + data.total);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    console.error('Status:', status);
                }
            });
        }

        function closeCart() {
            document.getElementById("cartSidebar").style.width = "0";
            document.getElementById("overlay").style.display = "none";
        }

        function addToCart(productId, priceId, quantity) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}&price_id=${priceId}&quantity=${quantity}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        document.querySelector('.nav-link[onclick="openCart()"]').textContent = `Cart (${data.cart_count})`;
                        console.log('Product added to cart successfully');
                        console.log(productId, priceId, quantity);
                    } else {
                        // Handle errors
                        console.error('Error adding product to cart:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function removeProduct(productId) {
            $.ajax({
                url: 'update_cart.php',
                type: 'POST',
                data: {
                    product_id: productId,
                    action: 'remove'
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (!data.error) {
                        // Remove product from cart display
                        $('#product-' + productId).remove();
                        $('#totalPrice').text('Total: ₱' + data.newTotal);

                        // Update cart count
                        document.querySelector('.nav-link[onclick="openCart()"]').textContent = `Cart (${data.cart_count})`;
                    } else {
                        console.error('Error removing product from cart:', data.error);
                    }
                }
            });
        }

        function updateCartCacheAndRedirect() {
            $.ajax({
                url: 'update_cart_cache.php',
                type: 'POST',
                dataType: 'json', // Ensure jQuery treats the response as JSON
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'cart-to-checkout.php';
                    } else {
                        console.error('Error updating cart cache:', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating cart cache:', error);
                }
            });
        }

    </script>

</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm header_navbar" id="mainNav">
        <div class="container px-5">
            <a class="navbar-brand fw-bold" href="index.php">
                <img src="assets/img/PawrSale_TextLogo.png" style="max-width:100%; max-height: 50px;">
            </a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto me-4 my-3 my-lg-0">
                    <li class="nav-item"><a class="nav-link me-lg-3 <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3 <?php echo ($current_page == 'about-us.php') ? 'active' : ''; ?>" href="about-us.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3 <?php echo ($current_page == 'contact-us.php') ? 'active' : ''; ?>" href="contact-us.php">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3 <?php echo ($current_page == 'products.php') ? 'active' : ''; ?>" href="products.php">Products</a></li>
                    <?php 
                        // Check if the current page is not 'cart-to-checkout.php', then display the Cart navigation item
                        if(basename($_SERVER['PHP_SELF']) != 'cart-to-checkout.php') { 
                    ?>
                        <li class="nav-item" onmouseover="headerChangeCartCol()" onmouseout="headerRestoreCartCol()" >
                        <div style="cursor: pointer;display:  flex;justify-content: center;flex-direction: row; align-items: center;">
                            <img id="header_cart" src="assets/img/Icon_Cart.png" class="img_icons_M">
                            <a id="cart_navItem" class="nav-link me-lg-3 <?php echo ($current_page == 'cart.php') ? 'active' : ''; ?>"  onclick="openCart()">
                                    Cart
                                    (<?php echo $cart_count; ?>)
                                </a>
                        </div>
                        </li>
                    <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


    <!-- Overlay -->
    <div id="overlay" class="overlay" onclick="closeCart()"></div>

    <!-- Cart Sidebar -->
    <div id="cartSidebar" class="cart-sidebar">
        <div style="height:35px;"></div>
        <div class="row">
            <div class="col-lg-9" style="display: flex; justify-content: start; align-items: center;">
                <h2 style="margin-left: 10px;">Shopping Cart</h2>
            </div>
            <div class="col-lg-3" style="display: flex; justify-content: end;">
                <a href="javascript:void(0)" class="closebtn" onclick="closeCart()">&times;</a>
            </div>
        </div>
        <div id="cartItems" style="margin-bottom: 60px;">
            <!-- Cart items will be dynamically loaded here -->
        </div>
        <!-- Wrapper for the Checkout link -->
        <div class="checkoutWrapper">
            <div class="row">
                <div class="col-lg-7" style="display: flex; align-items: center;">
                    <table>
                        <th> <a href="javascript:void(0)" onclick="updateCartCacheAndRedirect()" style="margin-left: 10px;">Checkout</a> </th>
                        <th> <img src="assets/img/Icon_Checkout_Go.png" alt="" class="img_icons_L"> </th>
                    </table>
                </div>
                <div class="col-lg-5 total_price_container" style="display: flex; align-items: center;">
                    <p id="totalPrice">Total: ₱0.00</p>
                </div>
            </div>
        </div>
    </div>
    <script src="js/scripts.js"></script>
</body>

</html>
