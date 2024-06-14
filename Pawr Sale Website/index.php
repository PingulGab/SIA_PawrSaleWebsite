<?php
include 'header.php';
require_once 'update_cache.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home - Pawr Sale</title>

    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Concert One&display=swap">

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body id="page-top">

    <div class="snap">

        <!-- Basic features section-->
        <section class="d-flex align-items-center justify-content-center px-5 bg-light index_page_sect"
            style="background-image: url('assets/img/Background_PetAccessories.jpg'); background-size: cover;">
            <div class="container px-5">
                <div class="row gx-5 align-items-center justify-content-center justify-content-lg-between">
                    <div class="col-12 text-center">
                        <h1 class="display-1 lh-1 mb-4" style="color: white;">Welcome to Pawr Sale</h1>
                        <p class="lead mb-5 mb-lg-0" style="color: white; font-weight: bold;"> We specialize in
                            affordable dog accessories and toys to keep tails wagging! </p>
                        <p class="lead mb-5 mb-lg-0" style="color: white; font-weight: bold;"> Join us in creating
                            moments of fun and happiness for your furry friend without breaking the bank!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mashead header-->
        <section class="index_page_sect home_secondSection">
            <div class="container px-5" style="margin-top: 25px;">
                <div class="row text-center">
                    <h1 class="display-1 lh-1" style="margin-bottom: 0px;">Your Dog's Joy Fuels Ours</h1>
                </div>
                <div class="d-flex align-items-center justify-content-center container px-5" style="margin-top: 50px">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6">
                            <!-- Mashead text and app badges-->
                            <div class="mb-5 mb-lg-0 text-center text-lg-start">
                                <h1 class="display-5 lh-1 mb-3">Forge Unforgettable Bonds with Your Furry Companion</h1>
                                <p class="lead fw-normal text-muted" style="text-align: justify">Experience the joy of
                                    creating lasting memories with your beloved canine companion through Pawr Sale's
                                    premium selection of dog toys.</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="device" data-device="iPhoneX" data-orientation="landscape" data-color="black">
                                <div class="screen bg-black"
                                    style="top: 0%; left: 0%; width:100%; height: 90%; margin-top: 10px; border-radius: 30px; display:flex; justify-content: center;">
                                    <video muted="muted" autoplay="" loop=""
                                        style="max-width: 100%; height: 105%; border-radius: 50px;">
                                        <source src="assets/img/Dog_Playing.mp4" type="video/mp4" />
                                    </video>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center container px-5 addPaddingLarge">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6 text-center">
                            <img src="assets/img/Home_Eating.png"
                                style="min-height: 100px; max-height:200px; width:auto">
                        </div>
                        <div class="col-lg-6">
                            <div class="text-center text-lg-start">
                                <h1 class="display-5 lh-1">Man's Bestfriend</h1>
                                <p class="lead fw-normal text-muted" style="margin-bottom: 0px;">We believe that every
                                    wag of a tail, every joyful bark, is a testament to the unconditional love and
                                    unwavering loyalty that dogs bring into our lives. Explore our collection and deepen
                                    the connection with your furry friend today.</p>
                                <button class="home_Btn"> <a href="products.php"> Shop Now </a></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- App features section-->
        <section class="index_page_sect removePadding">
            <section id="features">
                <div class="container px-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-8 order-lg-1 mb-5 mb-lg-0">
                            <div class="container-fluid px-5">
                                <div class="row gx-5">
                                    <div class="col-md-6 mb-5">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <img class="mb-3" src="assets/img/Home_Heart.png" style="height: 70px;"
                                                alt="heart">
                                            <h3 class="font-alt">Satisfaction</h3>
                                            <p class="lead text-muted mb-0">Ready to use products that dogs surely love!
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-5">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <img class="mb-3" src="assets/img/Home_DogSafety.png" style="height: 70px;"
                                                alt="safety">
                                            <h3 class="font-alt">Safe Toys</h3>
                                            <p class="lead text-muted mb-0">Our product are thoroughly tested for safety
                                                hazards!</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-5">
                                    <div class="col-md-6 mb-5 mb-md-0">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <img class="mb-3" src="assets/img/Home_SaveMoney.png" style="height: 70px;"
                                                alt="save money">
                                            <h3 class="font-alt">Save Money</h3>
                                            <p class="lead text-muted mb-0">We offer competitive pricing for high
                                                quality products.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <img class="mb-3" src="assets/img/Home_Delivery.png" style="height: 70px;"
                                                alt="heart">
                                            <h3 class="font-alt">Fast Delivery</h3>
                                            <p class="lead text-muted mb-0">Receive your order within 2-3 business days!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center" style="margin-top: 50px;">
                                <button class="home_Btn" style="width: 50%"> <a href="products.php"> Shop Now
                                    </a></button>
                            </div>
                        </div>
                        <div class="col-lg-4 order-lg-0">
                            <!-- Features section device mockup-->
                            <div class="features-device-mockup hideMeNow">
                                <svg class="circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="circleGradient" gradientTransform="rotate(45)">
                                            <stop class="gradient-start-color" offset="0%"></stop>
                                            <stop class="gradient-end-color" offset="100%"></stop>
                                        </linearGradient>
                                    </defs>
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg><svg class="shape-1 d-none d-sm-block" viewBox="0 0 240.83 240.83"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03"
                                        transform="translate(120.42 -49.88) rotate(45)"></rect>
                                    <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03"
                                        transform="translate(-49.88 120.42) rotate(-45)"></rect>
                                </svg><svg class="shape-2 d-none d-sm-block" viewBox="0 0 100 100"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <div class="device-wrapper">
                                    <div class="device" data-device="iPhoneX" data-orientation="portrait"
                                        data-color="black">
                                        <div class="screen bg-black">
                                            <video muted="muted" autoplay="" loop=""
                                                style="max-width: 100%; height: 100%">
                                                <source src="assets/img/Home_DogReview.mp4" type="video/mp4" />
                                            </video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quote/testimonial aside-->
                <aside class="text-center bg-gradient-primary-to-secondary"
                    style="height: 200px; padding-top: 30px; padding-bottom: 15px;">
                    <div class="px-5">
                        <div class="row gx-5 justify-content-center">
                            <div class="col-xl-12">
                                <div class="h2 fs-1 text-white mb-4">
                                    <h1 class="display-7"> Affiliated Partners </h1>
                                    <div>
                                        <img src="assets/img/Home_Partners_1.png" class="home_partnerIcon" alt="Bark"
                                            draggable="false">
                                        <img src="assets/img/Home_Partners_2.png" class="home_partnerIcon" alt="Bow&Wow"
                                            draggable="false">
                                        <img src="assets/img/Home_Partners_3.png" class="home_partnerIcon"
                                            alt="West Paw" draggable="false">
                                        <img src="assets/img/Home_Partners_5.png" class="home_partnerIcon" alt="Vetcove"
                                            draggable="false">
                                        <img src="assets/img/Home_Partners_6.png" class="home_partnerIcon"
                                            alt="Benebone" draggable="false">
                                        <img src="assets/img/Home_Partners_8.png" class="home_partnerIcon"
                                            alt="PetExpress" draggable="false">
                                        <img src="assets/img/Home_Partners_9.png" class="home_partnerIcon"
                                            alt="Pedigree" draggable="false">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </div>
</body>

</html>