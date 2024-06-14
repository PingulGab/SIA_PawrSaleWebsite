/*!
 * Start Bootstrap - New Age v6.0.7 (https://startbootstrap.com/theme/new-age)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-new-age/blob/master/LICENSE)
 */
//
// Scripts
//

window.addEventListener("DOMContentLoaded", (event) => {
  // Activate Bootstrap scrollspy on the main nav element
  const mainNav = document.body.querySelector("#mainNav");
  if (mainNav) {
    new bootstrap.ScrollSpy(document.body, {
      target: "#mainNav",
      offset: 74,
    });
  }

  // Collapse responsive navbar when toggler is visible
  const navbarToggler = document.body.querySelector(".navbar-toggler");
  const responsiveNavItems = [].slice.call(
    document.querySelectorAll("#navbarResponsive .nav-link")
  );
});

/* =============================    Popup Code    ========================================= */

// Function to show the popup and overlay
function showPopup(productId, priceId) {
  // Display the overlay and popup
  document.getElementById("popup-overlay").style.display = "block";
  document.getElementById("popup").style.display = "block";

  // Fetch the content of product_details with the product ID and price ID parameters
  fetch("product_details.php?product_id=" + productId + "&price_id=" + priceId)
    .then((response) => response.text())
    .then((data) => {
      // Inject the fetched content into the popup
      document.getElementById("popup").innerHTML =
        '<span class="close-btn" id="close-popup">X</span>' + data;
      // Add event listener to close button inside the popup
      document
        .getElementById("close-popup")
        .addEventListener("click", hidePopup);
    })
    .catch((error) => {
      console.error("Error:", error);
    });

  // Add event listener to close the popup when clicking outside of it
  document.getElementById("popup-overlay").addEventListener("click", hidePopup);
}

// Function to hide the popup and overlay
function hidePopup() {
  document.getElementById("popup-overlay").style.display = "none";
  document.getElementById("popup").style.display = "none";
}

/* =============================    Products.php     ========================================= */

/* =============================    Product_Details.php     ========================================= */

function incrementQuantity(inputId) {
  var input = document.getElementById(inputId);
  input.value = parseInt(input.value) + 1;
  updateTotalPrice(inputId);
}

function decrementQuantity(inputId) {
  var input = document.getElementById(inputId);
  if (input.value > 1) {
    input.value = parseInt(input.value) - 1;
    updateTotalPrice(inputId);
  }
}

function updateTotalPrice(inputId) {
  var input = document.getElementById(inputId);
  var unitPrice = parseFloat(input.getAttribute("data-price"));
  var quantity = parseInt(input.value);
  var total = unitPrice * quantity;
  document.getElementById("total-price").textContent =
    "₱" + total.toFixed(2);
}

function checkMinMax(input) {
  // Get the entered value
  var value = parseInt(input.value);

  // Check if the entered value is less than 1, then set it to 1
  if (value < 1 || isNaN(value)) {
    alert("Quantity can not be less than 1.");
    input.value = 1;
  }
  // Check if the entered value is greater than 99, then set it to 99
  else if (value > 99) {
    alert("Quantity can not be greater than 99.");
    input.value = 99;
  }
}

/* =============================    Cart.php     ========================================= */

function updateQuantity(productId, delta) {
  var input = document.getElementById("quantity-" + productId);
  var newQuantity = parseInt(input.value) + delta;

  if (newQuantity >= 1 && newQuantity <= 99) {
    input.value = newQuantity;

    var subtotalElement = document.getElementById("subtotal-" + productId);
    var unitPrice = parseFloat(subtotalElement.getAttribute("data-unit-price"));

    var newSubtotal = unitPrice * newQuantity;
    subtotalElement.textContent =
      numberWithCommas(newSubtotal.toFixed(2));

    // Update total immediately
    calculateTotal();

    $.ajax({
      url: "update_cart.php",
      type: "POST",
      data: {
        product_id: productId,
        action: "update",
        quantity: newQuantity,
      },
      success: function (data) {
        // Server response is received, additional actions can be handled if needed
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
        console.error("Status:", status);
      },
    });
  }
}

function toggleEdit(productId) {
  var input = document.getElementById("quantity-" + productId);
  var button = document.getElementById("edit-" + productId);
  var img = button.querySelector(".img_icons_S");

  var hiddenInput = img.getAttribute("data-hidden-input");

  if (hiddenInput === "Edit") {
    input.disabled = false;
    input.focus();
    img.setAttribute("data-hidden-input", "Done");
    img.src = "assets/img/Icon_Checkout_Save.png"; // Change the source to the "Done" image

    // Add event listener for the "Enter" key
    input.addEventListener("keypress", function (event) {
      if (event.key === "Enter") {
        finalizeEdit(productId);
      }
    });
  } else {
    finalizeEdit(productId);
  }
}

function finalizeEdit(productId) {
  var input = document.getElementById("quantity-" + productId);
  var button = document.getElementById("edit-" + productId);
  var img = button.querySelector(".img_icons_S");

  input.disabled = true;
  img.setAttribute("data-hidden-input", "Edit");
  img.src = "assets/img/Icon_Checkout_Edit.png"; // Change the source to the "Edit" image

  var newQuantity = parseInt(input.value);
  if (newQuantity > 99) {
    newQuantity = 99;
    input.value = 99;
  }

  if (newQuantity < 1 || isNaN(newQuantity)) {
    newQuantity = 1;
    input.value = 1;
  }

  var subtotalElement = document.getElementById("subtotal-" + productId);
  var unitPrice = parseFloat(subtotalElement.getAttribute("data-unit-price"));

  var newSubtotal = unitPrice * newQuantity;
  subtotalElement.textContent =
    numberWithCommas(newSubtotal.toFixed(2));

  // Update total immediately
  calculateTotal();

  $.ajax({
    url: "update_cart.php",
    type: "POST",
    data: {
      product_id: productId,
      action: "update",
      quantity: newQuantity,
    },
      success: function (data) {
          // Server response is received, additional actions can be handled if needed
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
        console.error("Status:", status);
    },
  });
}

function addToCart(productId, priceId, quantity) {
  $("#prd_addtocart").text("Adding...");
  fetch("add_to_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `product_id=${productId}&price_id=${priceId}&quantity=${quantity}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Update cart count
        document.querySelector(
          '.nav-link[onclick="openCart()"]'
        ).textContent = `Cart (${data.cart_count})`;

        hidePopup();
      } else {
        // Handle errors
        console.error("Error adding product to cartsssssss:", data.error);
      }
      $("#prd_addtocart").text("Adding...");
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function removeProduct(productId) {
  // Change the text of the remove button for the specific product to "Removing"
  $("#cart_removeButton-" + productId).text("Removing");

  // Send AJAX request to remove the product from the cart
  $.ajax({
    url: "update_cart.php",
    type: "POST",
    data: {
      product_id: productId,
      action: "remove",
    },
    success: function (response) {
      if (!response.error) {
        // Remove product from cart display
        $("#product-" + productId).remove();
        $("#totalPrice").text("Total: ₱" + response.newTotal);

        // Update cart count
        document.querySelector(
          '.nav-link[onclick="openCart()"]'
        ).textContent = `Cart (${response.cart_count})`;
      } else {
        console.error("Error removing product from cart:", response.error);
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX request failed:", error);
      console.error("Status:", status);
    },
  });
}

function calculateTotal() {
  var total = 0;
  document.querySelectorAll('[id^="subtotal-"]').forEach(function (element) {
    var subtotalText = element.textContent.trim();
    var subtotal = parseFloat(subtotalText.replace("Subtotal ₱", "").replace(/,/g, ""));
    if (!isNaN(subtotal)) {
      total += subtotal;
    } else {
      console.error("Failed to parse subtotal:", subtotalText);
    }
  });
  document.getElementById("totalPrice").textContent =
    "Total: ₱" + numberWithCommas(total.toFixed(2));
}


function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

//! MOUSE CHANGE

function changeRemoveImage(identifiedIMG) {
  document.getElementById('removeIMG-'+identifiedIMG).src = 'assets/img/Icon_Checkout_Remove_Hover.png';
}

function restoreRemoveImage(identifiedIMG) {
  document.getElementById('removeIMG-'+identifiedIMG).src = 'assets/img/Icon_Checkout_Remove.png';
}

//!Change Cart Color in Header
function headerChangeCartCol() {
  document.getElementById('header_cart').src = 'assets/img/Icon_Cart_Hover.png';
  document.getElementById('cart_navItem').style.color = '#f6ba01';
}

function headerRestoreCartCol() {
  document.getElementById('header_cart').src = 'assets/img/Icon_Cart.png';
  document.getElementById('cart_navItem').style.color = '';
}


//! ====================================================================================================================

function ctoc_updateQuantity(productId, delta) {
  var input = document.getElementById("quantity-" + productId);
  var newQuantity = parseInt(input.value) + delta;

  if (newQuantity >= 1 && newQuantity <= 99) {
    input.value = newQuantity;

    var subtotalElement = document.getElementById("subtotal-" + productId);
    var unitPrice = parseFloat(subtotalElement.getAttribute("data-unit-price"));

    var newSubtotal = unitPrice * newQuantity;
    subtotalElement.textContent = numberWithCommas(newSubtotal.toFixed(2));

    // Update total immediately
    ctoc_calculateTotal();

    $.ajax({
      url: "update_cart.php",
      type: "POST",
      data: {
        product_id: productId,
        action: "update",
        quantity: newQuantity,
      },
      success: function (data) {
        // Server response is received, additional actions can be handled if needed
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
        console.error("Status:", status);
      },
    });
  }
}

function ctoc_toggleEdit(productId) {
  var input = document.getElementById("quantity-" + productId);
  var button = document.getElementById("edit-" + productId);
  var img = button.querySelector(".img_icons_S");

  var hiddenInput = img.getAttribute("data-hidden-input");

  if (hiddenInput === "Edit") {
    input.disabled = false;
    input.focus();
    img.setAttribute("data-hidden-input", "Done");
    img.src = "assets/img/Icon_Checkout_Save.png"; // Change the source to the "Done" image

    // Add event listener for the "Enter" key
    input.addEventListener("keypress", function (event) {
      if (event.key === "Enter") {
        ctoc_finalizeEdit(productId);
      }
    });
  } else {
    ctoc_finalizeEdit(productId);
  }
}

function ctoc_finalizeEdit(productId) {
  var input = document.getElementById("quantity-" + productId);
  var button = document.getElementById("edit-" + productId);
  var img = button.querySelector(".img_icons_S");

  input.disabled = true;
  img.setAttribute("data-hidden-input", "Edit");
  img.src = "assets/img/Icon_Checkout_Edit.png"; // Change the source to the "Edit" image

  var newQuantity = parseInt(input.value);
  if (newQuantity > 99) {
    newQuantity = 99;
    input.value = 99;
  }

  if (newQuantity < 1 || isNaN(newQuantity)) {
    newQuantity = 1;
    input.value = 1;
  }

  var subtotalElement = document.getElementById("subtotal-" + productId);
  var unitPrice = parseFloat(subtotalElement.getAttribute("data-unit-price"));

  var newSubtotal = unitPrice * newQuantity;
  subtotalElement.textContent = numberWithCommas(newSubtotal.toFixed(2));

  // Update total immediately
  ctoc_calculateTotal();

  $.ajax({
    url: "update_cart.php",
    type: "POST",
    data: {
      product_id: productId,
      action: "update",
      quantity: newQuantity,
    },
    success: function (data) {
      // Server response is received, additional actions can be handled if needed
    },
    error: function (xhr, status, error) {
      console.error("AJAX request failed:", error);
      console.error("Status:", status);
    },
  });
}

function ctoc_removeProduct(productId) {
  // Change the text of the remove button for the specific product to "Removing"
  $("#ctoc_removeBtn-" + productId).text("Removing");

  // Send AJAX request to remove the product from the cart
  $.ajax({
    url: "update_cart.php",
    type: "POST",
    data: {
      product_id: productId,
      action: "remove",
    },
    success: function (response) {
      if (!response.error) {
        // Remove product from cart display
        $("#product-" + productId).remove();
        $("#ctoc_totalPrice").text("₱" + response.newTotal);

      } else {
        console.error("Error removing product from cart:", response.error);
      }
    },
  });
}

function ctoc_calculateTotal() {
  var total = 0;
  document.querySelectorAll('[id^="subtotal-"]').forEach(function (element) {
    var subtotalText = element.textContent.trim();
    var subtotal = parseFloat(
      subtotalText.replace("Subtotal ₱", "").replace(/,/g, "")
    );
    if (!isNaN(subtotal)) {
      total += subtotal;
    } else {
      console.error("Failed to parse subtotal:", subtotalText);
    }
  });
  document.getElementById("ctoc_totalPrice").textContent =
    "₱" + numberWithCommas(total.toFixed(2));
}

