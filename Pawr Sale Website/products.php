<?php
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products - Pawr Sale</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<div>
    <div class="masthead">
        <div class="container">
        <img src="assets/img/Product_Banner.png" style="box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.25);">
            <div class="row container">
                <div class="col-lg-3">
                <h1 class="display-4"> Products </h1>
                    <div class="product_filterContainer">
                        <h2 style="color:#f6ba01;">Categories</h2>
                        <div id="category-links"></div>
                    </div>
                </div>

                <div class="col-lg-9">
                
                    <div class="products_container" id="products-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="popup-overlay" id="popup-overlay"></div>

<div class="popup" id="popup">
    <span class="close-btn" id="close-popup">X</span>
    <div id="popup-content"></div>
</div>

<script src="js/scripts.js"></script>
<script>
// Preload product details and store in a global object
let productDetailsCache = {};

document.addEventListener('DOMContentLoaded', function() {
    fetch('load_products.php')
        .then(response => response.json())
        .then(data => {
            let categorized_products = data.products.reduce((acc, product) => {
                let category = product.category.charAt(0).toUpperCase() + product.category.slice(1);
                if (!acc[category]) acc[category] = [];
                acc[category].push(product);
                return acc;
            }, {});

            let categoryLinksHtml = '';
            let productsContainerHtml = '';

            for (let category in categorized_products) {
                categoryLinksHtml += `<a href='#${category}' id='link-${category}' class='category-link'>${category}</a><br><br>`;
                productsContainerHtml += `<section class='categorized_products' id='${category}'>
                    <h3>${category}</h3><div class='row'>`;
                categorized_products[category].forEach((product, index) => {
                    if (index % 3 === 0 && index !== 0) productsContainerHtml += '</div><div class="row">';
                    productsContainerHtml += `<div class="col-lg-4" style="padding: 0px;">
                        <div class="clickable-div" onclick="showPopup('${product.id}', '${product.price_id}')">
                            <div class="image-container" style="position: relative;">
                                ${product.images.map(image => `<div class='gradient-overlay'></div><img src='${image}' class='product-image'>`).join('')}
                            </div>
                            <div class="prod_price" style="position: absolute; bottom: 10px; right: 10px;">
                                ${formatCurrency(product.price, product.currency)}
                            </div>
                            <table class="product_details_table" style="width: 100%;">
                                <tr>
                                    <td><h4 style='margin-bottom: 0px !important; font-size: 18px !important;'>${product.name}</h4></td>
                                </tr>
                                <tr>
                                    <td><p class='text-muted mb-0 prod_description' style='margin-bottom: 0px !important;'>${truncateDescription(product.description)}</p></td>
                                </tr>
                            </table>
                        </div>
                    </div>`;
                });
                productsContainerHtml += '</div></section>';
            }

            document.getElementById('category-links').innerHTML = categoryLinksHtml;
            document.getElementById('products-container').innerHTML = productsContainerHtml;

            // Preload product details
            data.products.forEach(product => {
                fetch(`product_details.php?product_id=${product.id}&price_id=${product.price_id}`)
                    .then(response => response.text())
                    .then(details => {
                        productDetailsCache[product.id] = details;
                    });
            });

            // Add click event listener to category links
            document.querySelectorAll('.category-link').forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelectorAll('.category-link').forEach(link => link.style.fontWeight = 'normal');
                    this.style.fontWeight = 'bold';
                    document.querySelectorAll('.category-link').forEach(link => link.style.fontSize = 'inherit');
                    this.style.fontSize = '20px';
                });
            });

        })
        .catch(error => console.error('Error loading products:', error));
});

function formatCurrency(amount, currency) {
    let formatted_currency = currency.toUpperCase() === 'USD' ? '$' : (currency.toUpperCase() === 'PHP' ? '₱' : '');
    return `${formatted_currency}${(amount / 100).toFixed(2)}`;
}

function truncateDescription(description) {
    let max_characters = 160;
    return description.length > max_characters ? `${description.substring(0, max_characters)}...` : description;
}

function showPopup(productId, priceId) {
    document.getElementById('popup-content').innerHTML = productDetailsCache[productId] || '<div class="loading-container"><img src="assets/img/loading_goldenRetriever.gif" alt="running dog"></div>';
    document.getElementById('popup-overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
    // Fetch and update the product details in case they are not preloaded or have changed
    fetch(`product_details.php?product_id=${productId}&price_id=${priceId}`)
        .then(response => response.text())
        .then(details => {
            productDetailsCache[productId] = details;
            document.getElementById('popup-content').innerHTML = details;
        });

    document.getElementById('popup-overlay').addEventListener('click', hidePopup);
}

document.getElementById('close-popup').addEventListener('click', function() {
    document.getElementById('popup-overlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
});
</script>
</body>
</html>
