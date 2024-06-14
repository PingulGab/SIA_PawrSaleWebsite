<?php
include 'header.php'
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pawr Sale</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #000;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: xx-large;
            text-align: center;
        }

        p {
            font-size: large;
        }

        @media (max-width: 1200px) {
            .contact-box i {
                width: 90%;
            }
        }

        @media (max-width: 768px) {
            main {
                padding: 10px;
            }

            .contact-info-section {
                flex-direction: column;
                text-align: center;
            }

            .faqs-container {
                padding: 20px;
            }

            .accordion {
                font-size: 16px;
            }

            .contact-box i {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="masthead" style="margin-bottom: 0px;">
        <h1 style="text-align: center; font-size:xxx-large;">Contact Us</h1>
        <main>
            <section class="contact-info-section">
                <div class="contact-info-item">
                    <h3>Follow Us</h3>
                    <p class="social-media">
                        <a href="https://facebook.com"> <i class="fab fa-facebook"></i> </a>
                        <a href="https://x.com"> <i class="fab fa-twitter"></i> </a>
                        <a href="https://twitter.com"> <i class="fab fa-instagram"></i> </a>
                    </p>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-phone"></i>
                    <h3>Call Us</h3>
                    <p>(714) 367-8515</p>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <h3>Email Us</h3>
                    <p><a href="mailto:sia.pawrsale@gmail.com">sia.pawrsale@gmail.com</a></p>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Visit Us</h3>
                    <p>#6 Centerpointe Dr. Suite 700<br>La Palma CA 90623</p>
                </div>
            </section>

        </main>
        <section class="faqs-container">
            <div class="container">
                <div class="faqs-section">
                    <h2 style="text-align:center;">Have some questions? We've got you covered!</h2>
                    <button class="accordion">How secure is my information on Pawr Sale? <i
                            class="fas fa-plus"></i></button>
                    <div class="panel">
                        <p>We take your privacy very seriously. We ensure that your sensitive data is protected and
                            encrypted at all times.</p>
                    </div>
                    <button class="accordion">What should I do if I can't login to my account? <i
                            class="fas fa-plus"></i></button>
                    <div class="panel">
                        <p>If you can't login to your account, try resetting your password or contact our support team
                            for assistance.</p>
                    </div>
                    <button class="accordion">How can I track my order? <i class="fas fa-plus"></i></button>
                    <div class="panel">
                        <p>After placing an order, you will receive an email with a tracking number. You can use this
                            number on our website to track your order.</p>
                    </div>
                    <button class="accordion">Can I change or cancel my order? <i class="fas fa-plus"></i></button>
                    <div class="panel">
                        <p>Yes, you can change or cancel your order within 24 hours of placing it. Please contact our
                            customer service team for assistance.</p>
                    </div>
                    <button class="accordion">What is your return policy? <i class="fas fa-plus"></i></button>
                    <div class="panel">
                        <p>We offer a 30-day return policy for unused items in their original packaging. Please contact
                            our support team to initiate a return.</p>
                    </div>
                    <button class="accordion">Do you offer international shipping? <i class="fas fa-plus"></i></button>
                    <div class="panel">
                        <p>Yes, we offer international shipping to most countries. Shipping costs and delivery times
                            vary depending on the destination.</p>
                    </div>
                    <button class="accordion">How can I contact customer service? <i class="fas fa-plus"></i></button>
                    <div class="panel">
                        <p>You can contact our customer service team via email at support@pawrsale.com or call us at
                            +1-234-567-890.</p>
                    </div>
                </div>
            </div>
        </section>
        <main>
            <section class="need-help" style="padding: 0px;">
                <h2>Need more help? Send us an email</h2>
                <p>If we still haven't answered your question, you can contact us below and we will get back to you as
                    soon as possible.</p>
                <div class="contact-box">
                    <i class="fas fa-envelope" style="padding-top: 10px;"></i>
                    <h2 style="margin-bottom: 0px;">Send a message</h2>
                    <p><a href="mailto:sia.pawrsale@gmail.com">sia.pawrsale@gmail.com</a></p>
                </div>
            </section>
        </main>
        <div class="separator"></div>
        <section class="location-section" style="padding: 0px;">
            <div class="container">
                <h2>Want to reach us? Here's how to get there!</h2>
                <iframe class="map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3302.1963488773536!2d-118.04166908478804!3d33.84900628066171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80dd2b6e254ae4f5%3A0xf8f8f8f8f8f8f8f8!2s6%20Centerpointe%20Dr%20%23700%2C%20La%20Palma%2C%20CA%2090623%2C%20USA!5e0!3m2!1sen!2sin!4v1620835214226!5m2!1sen!2sin"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </section>
        <script>
            var acc = document.getElementsByClassName("accordion");
            for (var i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function () {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    var icon = this.querySelector("i");
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                        icon.classList.remove("fa-minus");
                        icon.classList.add("fa-plus");
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                        icon.classList.remove("fa-plus");
                        icon.classList.add("fa-minus");
                    }
                });
            }
        </script>
    </div>
</body>

</html>